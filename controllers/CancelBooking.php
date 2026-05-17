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

$booking_id = $_GET['id'] ?? 0;
$guest_id = $_SESSION['user_id'];

if ($booking_id > 0) {
    // Check if cancellation is allowed (at least 2 days before check-in)
    $checkSql = "SELECT checkin_date, loyalty_points_used FROM bookings WHERE id = ? AND guest_id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $booking_id, $guest_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $booking = $result->fetch_assoc();
    $checkStmt->close();
    
    if ($booking) {
        $checkin = new DateTime($booking['checkin_date']);
        $today = new DateTime();
        $diff = $today->diff($checkin)->days;
        
        if ($checkin <= $today) {
            $_SESSION['cancel_error'] = "Cannot cancel past or current bookings";
        } elseif ($diff < 2) {
            $_SESSION['cancel_error'] = "Cancellation must be at least 2 days before check-in";
        } else {
            // Cancel the booking
            $sql = "UPDATE bookings SET status = 'cancelled' WHERE id = ? AND guest_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $booking_id, $guest_id);
            
            if ($stmt->execute()) {
                // Refund loyalty points if any were used
                if ($booking['loyalty_points_used'] > 0) {
                    $pointsSql = "UPDATE users SET loyalty_points = loyalty_points + ? WHERE id = ?";
                    $pointsStmt = $conn->prepare($pointsSql);
                    $pointsStmt->bind_param("ii", $booking['loyalty_points_used'], $guest_id);
                    $pointsStmt->execute();
                    $pointsStmt->close();
                    
                    // Update session
                    $_SESSION['loyalty_points'] += $booking['loyalty_points_used'];
                    $_SESSION['cancel_success'] = "Booking cancelled. " . $booking['loyalty_points_used'] . " points refunded.";
                } else {
                    $_SESSION['cancel_success'] = "Booking cancelled successfully";
                }
                $stmt->close();
            } else {
                $_SESSION['cancel_error'] = "Cancellation failed";
            }
        }
    } else {
        $_SESSION['cancel_error'] = "Booking not found";
    }
}

$conn->close();
header("Location: ../views/guest/my_bookings.php");
exit();
?>