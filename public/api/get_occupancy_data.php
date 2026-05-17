<?php

header('Content-Type: application/json');
require_once '../../config/database.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["error" => "Access clearance validation exception protocol rejected request layer."]);
    exit();
}

$dbObj = new Database();
$db = $dbObj->getConnection();

$sql = "SELECT rt.name as type_name, 
        COUNT(CASE WHEN r.status = 'occupied' THEN 1 END) as occupied,
        COUNT(r.id) as total_rooms
        FROM room_types rt
        LEFT JOIN rooms r ON r.room_type_id = rt.id
        GROUP BY rt.id";

$result = $db->query($sql);
$data = [];

while($row = $result->fetch_assoc()) {
    $data[] = [
        "type_name" => $row['type_name'],
        "occupied" => intval($row['occupied']),
        "total_rooms" => intval($row['total_rooms'])
    ];
}

echo json_encode($data);