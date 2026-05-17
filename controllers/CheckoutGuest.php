<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'receptionist'){
    header("Location: ../views/receptionist/login.php");
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

$booking_id = $_GET['booking_id'] ?? 0;

if ($booking_id) {
    // Get booking details and total amount
    $sql = "SELECT b.*, bl.total_amount FROM bookings b 
            JOIN billing bl ON b.id = bl.booking_id 
            WHERE b.id = ? AND b.status = 'checked_in'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    $stmt->close();

    if ($booking) {
        // Calculate points (1 point per 100 currency)
        $points_earned = floor($booking['total_amount'] / 100);
        
        // Update user loyalty points
        $updatePoints = "UPDATE users SET loyalty_points = loyalty_points + ? WHERE id = ?";
        $stmt = $conn->prepare($updatePoints);
        $stmt->bind_param("ii", $points_earned, $booking['guest_id']);
        $stmt->execute();
        $stmt->close();

        // Record in history
        $historySql = "INSERT INTO loyalty_points_history (guest_id, booking_id, points_earned, balance_after) 
                       SELECT ?, ?, ?, loyalty_points FROM users WHERE id = ?";
        $histStmt = $conn->prepare($historySql);
        $histStmt->bind_param("iiii", $booking['guest_id'], $booking_id, $points_earned, $booking['guest_id']);
        $histStmt->execute();
        $histStmt->close();

        // Update booking status to checked_out
        $updateBooking = "UPDATE bookings SET status = 'checked_out' WHERE id = ?";
        $stmt = $conn->prepare($updateBooking);
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $stmt->close();

        // Update room status to dirty (if room assigned)
        if ($booking['room_id']) {
            $updateRoom = "UPDATE rooms SET status = 'dirty' WHERE id = ?";
            $stmt = $conn->prepare($updateRoom);
            $stmt->bind_param("i", $booking['room_id']);
            $stmt->execute();
            $stmt->close();
        }

        $_SESSION['checkout_success'] = "Guest checked out. $points_earned loyalty points awarded.";
    } else {
        $_SESSION['checkout_error'] = "Invalid booking or already checked out.";
    }
}

$conn->close();
header("Location: ../views/receptionist/dashboard.php");
exit();
?>