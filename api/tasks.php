<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

require_login(true);

$pdo = db();
$user = current_user();
$action = post_value('action', get_value('action'));

if ($_SERVER['REQUEST_METHOD'] === 'GET' || $action === 'list') {
    $priority = get_value('priority');
    $status = get_value('status');
    json_response([
        'success' => true,
        'tasks' => fetch_today_tasks($pdo, $priority, $status),
    ]);
}

if ($action === 'create') {
    $roomId = (int) post_value('room_id');
    $taskType = post_value('task_type');
    $priority = post_value('priority');
    $scheduledDate = post_value('scheduled_date');
    $notes = post_value('notes');

    if ($roomId <= 0 || !in_array($taskType, ['cleaning', 'inspection'], true) || !in_array($priority, ['normal', 'urgent'], true) || $scheduledDate === '') {
        json_response(['success' => false, 'message' => 'Please fill in all task fields correctly.'], 422);
    }

    $stmt = $pdo->prepare(
        "INSERT INTO housekeeping_tasks (room_id, task_type, priority, status, scheduled_date, notes, assigned_to, created_by, created_at)
         VALUES (:room_id, :task_type, :priority, 'pending', :scheduled_date, :notes, :assigned_to, :created_by, NOW())"
    );
    $stmt->execute([
        'room_id' => $roomId,
        'task_type' => $taskType,
        'priority' => $priority,
        'scheduled_date' => $scheduledDate,
        'notes' => $notes,
        'assigned_to' => $user['id'],
        'created_by' => $user['id'],
    ]);

    json_response(['success' => true, 'message' => 'Housekeeping task created successfully.']);
}

if ($action === 'update_status') {
    $taskId = (int) post_value('task_id');
    $status = post_value('status');

    if ($taskId <= 0 || !in_array($status, ['in_progress', 'completed'], true)) {
        json_response(['success' => false, 'message' => 'Invalid task status update.'], 422);
    }

    $taskStmt = $pdo->prepare("SELECT id, room_id, task_type FROM housekeeping_tasks WHERE id = ?");
    $taskStmt->execute([$taskId]);
    $task = $taskStmt->fetch();

    if (!$task) {
        json_response(['success' => false, 'message' => 'Task not found.'], 404);
    }

    $completionNotes = post_value('completion_notes');

    if ($status === 'in_progress') {
        $stmt = $pdo->prepare("UPDATE housekeeping_tasks SET status = 'in_progress' WHERE id = ?");
        $stmt->execute([$taskId]);

        $roomStmt = $pdo->prepare(
            "UPDATE rooms
             SET status = CASE
                    WHEN status IN ('maintenance', 'occupied', 'blocked') THEN status
                    ELSE 'in_progress'
                 END
             WHERE id = ?"
        );
        $roomStmt->execute([$task['room_id']]);

        json_response(['success' => true, 'message' => 'Task marked as in progress.']);
    }

    $stmt = $pdo->prepare(
        "UPDATE housekeeping_tasks
         SET status = 'completed',
             completion_notes = :completion_notes,
             completed_at = NOW(),
             completed_by = :completed_by
         WHERE id = :task_id"
    );
    $stmt->execute([
        'completion_notes' => $completionNotes,
        'completed_by' => $user['id'],
        'task_id' => $taskId,
    ]);

    if ($task['task_type'] === 'cleaning') {
        $roomStmt = $pdo->prepare("UPDATE rooms SET needs_inspection = 1 WHERE id = ?");
        $roomStmt->execute([$task['room_id']]);
    }

    json_response(['success' => true, 'message' => 'Task marked as completed.']);
}

if ($action === 'mark_ready') {
    $taskId = (int) post_value('task_id');

    $stmt = $pdo->prepare("SELECT room_id, status FROM housekeeping_tasks WHERE id = ?");
    $stmt->execute([$taskId]);
    $task = $stmt->fetch();

    if (!$task || $task['status'] !== 'completed') {
        json_response(['success' => false, 'message' => 'Only completed tasks can clear a room for check-in.'], 422);
    }

    $roomStmt = $pdo->prepare(
        "UPDATE rooms
         SET status = 'available',
             needs_inspection = 0,
             last_ready_at = NOW()
         WHERE id = ?"
    );
    $roomStmt->execute([$task['room_id']]);

    json_response(['success' => true, 'message' => 'Room marked clean and ready.']);
}

json_response(['success' => false, 'message' => 'Unsupported task action.'], 400);
