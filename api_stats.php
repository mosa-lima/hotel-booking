<?php

header('Content-Type: application/json');
require_once 'config/database.php';

$dbObj = new Database();
$db = $dbObj->getConnection();


$rooms = $db->query("SELECT COUNT(*) as total, COUNT(CASE WHEN status='available' THEN 1 END) as avail, COUNT(CASE WHEN status='occupied' THEN 1 END) as occ FROM rooms")->fetch_assoc();

$response = [
    'status' => 'success',
    'total_inventory' => intval($rooms['total'] ?? 0),
    'available' => intval($rooms['avail'] ?? 0),
    'occupied' => intval($rooms['occ'] ?? 0)
];

echo json_encode($response);
exit();