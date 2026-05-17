<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function json_response(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function current_date(): string
{
    return date('Y-m-d');
}

function current_datetime(): string
{
    return date('Y-m-d H:i:s');
}

function post_value(string $key, string $default = ''): string
{
    return trim((string) ($_POST[$key] ?? $default));
}

function get_value(string $key, string $default = ''): string
{
    return trim((string) ($_GET[$key] ?? $default));
}

function status_badge_class(string $status): string
{
    return match ($status) {
        'available', 'completed', 'resolved' => 'success',
        'dirty', 'pending' => 'warning',
        'in_progress' => 'info',
        'maintenance', 'urgent', 'high' => 'danger',
        'occupied' => 'dark',
        'blocked' => 'muted',
        default => 'default',
    };
}

function task_status_label(string $status): string
{
    return str_replace('_', ' ', ucfirst($status));
}

function room_can_be_ready(array $task): bool
{
    return $task['status'] === 'completed';
}

function fetch_dashboard_stats(PDO $pdo): array
{
    $dirtyRooms = (int) $pdo->query("SELECT COUNT(*) FROM rooms WHERE status = 'dirty'")->fetchColumn();
    $pendingInspection = (int) $pdo->query("SELECT COUNT(*) FROM rooms WHERE needs_inspection = 1")->fetchColumn();
    $openMaintenance = (int) $pdo->query("SELECT COUNT(*) FROM maintenance_reports WHERE status <> 'resolved'")->fetchColumn();

    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM housekeeping_tasks
         WHERE status = 'completed' AND DATE(completed_at) = CURDATE()"
    );
    $stmt->execute();
    $completedToday = (int) $stmt->fetchColumn();

    return [
        'dirty_rooms' => $dirtyRooms,
        'pending_inspection' => $pendingInspection,
        'open_maintenance' => $openMaintenance,
        'tasks_completed_today' => $completedToday,
    ];
}

function fetch_room_board(PDO $pdo): array
{
    $stmt = $pdo->query(
        "SELECT r.id, r.room_number, r.floor, r.type, r.status, r.needs_inspection,
                CASE
                    WHEN EXISTS (
                        SELECT 1
                        FROM housekeeping_tasks ht
                        WHERE ht.room_id = r.id
                          AND ht.status IN ('pending', 'in_progress')
                          AND ht.scheduled_date = CURDATE()
                    ) THEN 1
                    ELSE 0
                END AS has_open_task
         FROM rooms r
         ORDER BY r.floor, r.room_number"
    );

    return $stmt->fetchAll();
}

function fetch_today_tasks(PDO $pdo, string $priority = '', string $status = ''): array
{
    $sql = "SELECT ht.id, ht.task_type, ht.priority, ht.status, ht.scheduled_date, ht.notes,
                   ht.completion_notes, ht.completed_at, r.room_number, r.status AS room_status,
                   u.full_name AS assigned_to_name
            FROM housekeeping_tasks ht
            INNER JOIN rooms r ON r.id = ht.room_id
            LEFT JOIN users u ON u.id = ht.assigned_to
            WHERE ht.scheduled_date = CURDATE()";
    $params = [];

    if ($priority !== '') {
        $sql .= " AND ht.priority = :priority";
        $params['priority'] = $priority;
    }

    if ($status !== '') {
        $sql .= " AND ht.status = :status";
        $params['status'] = $status;
    }

    $sql .= " ORDER BY FIELD(ht.priority, 'urgent', 'normal'), FIELD(ht.status, 'pending', 'in_progress', 'completed'), r.room_number";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function fetch_open_maintenance(PDO $pdo): array
{
    $stmt = $pdo->query(
        "SELECT mr.id, mr.description, mr.severity, mr.status, mr.reported_at, r.room_number
         FROM maintenance_reports mr
         INNER JOIN rooms r ON r.id = mr.room_id
         WHERE mr.status <> 'resolved'
         ORDER BY FIELD(mr.severity, 'high', 'medium', 'low'), mr.reported_at ASC"
    );

    return $stmt->fetchAll();
}

function fetch_upcoming_activity(PDO $pdo): array
{
    $today = current_date();
    $tomorrow = date('Y-m-d', strtotime('+1 day'));

    $checkOutStmt = $pdo->prepare(
        "SELECT b.id, b.guest_name, b.checkout_at, b.status, r.room_number
         FROM bookings b
         INNER JOIN rooms r ON r.id = b.room_id
         WHERE DATE(b.checkout_at) IN (?, ?)
           AND b.status IN ('confirmed', 'checked_in')
         ORDER BY b.checkout_at ASC"
    );
    $checkOutStmt->execute([$today, $tomorrow]);

    $checkInStmt = $pdo->prepare(
        "SELECT b.id, b.guest_name, b.checkin_at, b.status, r.room_number
         FROM bookings b
         INNER JOIN rooms r ON r.id = b.room_id
         WHERE DATE(b.checkin_at) IN (?, ?)
           AND b.status = 'confirmed'
         ORDER BY b.checkin_at ASC"
    );
    $checkInStmt->execute([$today, $tomorrow]);

    return [
        'checkouts' => $checkOutStmt->fetchAll(),
        'checkins' => $checkInStmt->fetchAll(),
    ];
}

function fetch_daily_report(PDO $pdo): array
{
    $report = [
        'assigned' => 0,
        'completed' => 0,
        'pending' => 0,
        'rooms_cleared' => 0,
        'tasks' => [],
    ];

    $stmt = $pdo->query(
        "SELECT ht.id, ht.task_type, ht.priority, ht.status, ht.scheduled_date, ht.completed_at,
                r.room_number, u.full_name AS assigned_to_name
         FROM housekeeping_tasks ht
         INNER JOIN rooms r ON r.id = ht.room_id
         LEFT JOIN users u ON u.id = ht.assigned_to
         WHERE ht.scheduled_date = CURDATE()
         ORDER BY r.room_number"
    );
    $tasks = $stmt->fetchAll();

    foreach ($tasks as $task) {
        $report['assigned']++;
        if ($task['status'] === 'completed') {
            $report['completed']++;
        } else {
            $report['pending']++;
        }
    }

    $report['tasks'] = $tasks;

    $readyStmt = $pdo->prepare(
        "SELECT COUNT(*) FROM rooms
         WHERE status = 'available'
           AND last_ready_at IS NOT NULL
           AND DATE(last_ready_at) = CURDATE()"
    );
    $readyStmt->execute();
    $report['rooms_cleared'] = (int) $readyStmt->fetchColumn();

    return $report;
}

function fetch_room_history(PDO $pdo, int $roomId = 0): array
{
    $sql = "SELECT ht.id, ht.task_type, ht.priority, ht.completed_at, ht.completion_notes,
                   r.room_number, u.full_name AS completed_by_name
            FROM housekeeping_tasks ht
            INNER JOIN rooms r ON r.id = ht.room_id
            LEFT JOIN users u ON u.id = ht.completed_by
            WHERE ht.status = 'completed'";
    $params = [];

    if ($roomId > 0) {
        $sql .= " AND ht.room_id = :room_id";
        $params['room_id'] = $roomId;
    }

    $sql .= " ORDER BY ht.completed_at DESC LIMIT 100";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}
