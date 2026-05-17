<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

require_login(true);

$pdo = db();
$action = post_value('action', get_value('action'));

if ($_SERVER['REQUEST_METHOD'] === 'GET' || $action === 'list') {
    json_response([
        'success' => true,
        'issues' => fetch_open_maintenance($pdo),
    ]);
}

if ($action === 'create') {
    $roomId = (int) post_value('room_id');
    $description = post_value('description');
    $severity = post_value('severity');

    if ($roomId <= 0 || $description === '' || !in_array($severity, ['low', 'medium', 'high'], true)) {
        json_response(['success' => false, 'message' => 'Please complete the maintenance form correctly.'], 422);
    }

    $stmt = $pdo->prepare(
        "INSERT INTO maintenance_reports (room_id, description, severity, status, reported_at)
         VALUES (:room_id, :description, :severity, 'open', NOW())"
    );
    $stmt->execute([
        'room_id' => $roomId,
        'description' => $description,
        'severity' => $severity,
    ]);

    $roomStmt = $pdo->prepare("UPDATE rooms SET status = 'maintenance', needs_inspection = 0 WHERE id = ?");
    $roomStmt->execute([$roomId]);

    json_response(['success' => true, 'message' => 'Maintenance issue logged successfully.']);
}

if ($action === 'update_status') {
    $issueId = (int) post_value('issue_id');
    $status = post_value('status');

    if ($issueId <= 0 || !in_array($status, ['in_progress', 'resolved'], true)) {
        json_response(['success' => false, 'message' => 'Invalid maintenance update.'], 422);
    }

    $stmt = $pdo->prepare("SELECT room_id FROM maintenance_reports WHERE id = ?");
    $stmt->execute([$issueId]);
    $issue = $stmt->fetch();

    if (!$issue) {
        json_response(['success' => false, 'message' => 'Maintenance report not found.'], 404);
    }

    $updateStmt = $pdo->prepare("UPDATE maintenance_reports SET status = ? WHERE id = ?");
    $updateStmt->execute([$status, $issueId]);

    if ($status === 'resolved') {
        $roomStmt = $pdo->prepare("UPDATE rooms SET status = 'available', last_ready_at = NOW() WHERE id = ?");
        $roomStmt->execute([$issue['room_id']]);
    }

    json_response(['success' => true, 'message' => 'Maintenance status updated successfully.']);
}

json_response(['success' => false, 'message' => 'Unsupported maintenance action.'], 400);
