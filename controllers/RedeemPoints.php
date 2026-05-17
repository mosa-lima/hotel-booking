<?php
// This is called via AJAX or as part of booking flow to apply loyalty points discount
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guest'){
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hotel_booking_system";
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB error']);
    exit();
}

$guest_id = $_SESSION['user_id'];
$points_to_redeem = $_GET['points'] ?? 0;
$total_amount = $_GET['total_amount'] ?? 0;

// Get available points
$sql = "SELECT loyalty_points FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $guest_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$available_points = $user['loyalty_points'];

if ($points_to_redeem > $available_points) {
    echo json_encode(['success' => false, 'message' => 'Not enough points']);
    exit();
}

// Typically 100 points = $1 discount (adjust as needed)
$discount = floor($points_to_redeem / 100);
$new_total = max(0, $total_amount - $discount);

echo json_encode([
    'success' => true,
    'discount' => $discount,
    'new_total' => $new_total,
    'points_used' => $points_to_redeem
]);

$conn->close();
?>