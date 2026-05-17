<?php
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guest'){
    header("Location: ../views/guest/login.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hotel_booking_system";
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

include "../models/ServiceModel.php";
$serviceModel = new ServiceModel($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'] ?? 0;
    $guest_id = $_SESSION['user_id'];
    $service_type = $_POST['service_type'] ?? '';
    $description = trim($_POST['description'] ?? '');
    
    // Validation
    $error = "";
    if (!$booking_id) $error = "Please select a booking";
    elseif (empty($service_type)) $error = "Please select service type";
    elseif (empty($description)) $error = "Please provide description";
    
    if ($error) {
        $_SESSION['service_error'] = $error;
        header("Location: ../views/guest/service_request.php");
        exit();
    }
    
    // Get room_id from the booking (if assigned)
    $roomSql = "SELECT room_id FROM bookings WHERE id = ? AND guest_id = ? AND status = 'checked_in'";
    $roomStmt = $conn->prepare($roomSql);
    $roomStmt->bind_param("ii", $booking_id, $guest_id);
    $roomStmt->execute();
    $roomResult = $roomStmt->get_result();
    $roomRow = $roomResult->fetch_assoc();
    $room_id = $roomRow['room_id'] ?? null;
    $roomStmt->close();
    
    if ($serviceModel->addServiceRequest($booking_id, $guest_id, $room_id, $service_type, $description)) {
        $_SESSION['service_success'] = "Service request submitted successfully";
    } else {
        $_SESSION['service_error'] = "Failed to submit request";
    }
    
    $conn->close();
    header("Location: ../views/guest/service_request.php");
    exit();
}
?>