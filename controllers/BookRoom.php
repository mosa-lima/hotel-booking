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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_type_id = $_POST['room_type_id'] ?? 0;
    $checkin = $_POST['checkin'] ?? '';
    $checkout = $_POST['checkout'] ?? '';
    $guests = $_POST['guests'] ?? 1;
    $price_per_night = $_POST['price'] ?? 0;
    $special_requests = $_POST['special_requests'] ?? '';
    $guest_id = $_SESSION['user_id'];
    
    // Calculate total nights and base total price
    $date1 = new DateTime($checkin);
    $date2 = new DateTime($checkout);
    $nights = $date1->diff($date2)->days;
    $total_price = $nights * $price_per_night;
    
    // Apply loyalty points discount if requested
    $points_to_use = $_POST['redeem_points'] ?? 0;
    $points_used = 0;
    if ($points_to_use > 0) {
        $pointsSql = "SELECT loyalty_points FROM users WHERE id = ?";
        $pointsStmt = $conn->prepare($pointsSql);
        $pointsStmt->bind_param("i", $guest_id);
        $pointsStmt->execute();
        $pointsResult = $pointsStmt->get_result();
        $pointsRow = $pointsResult->fetch_assoc();
        $pointsStmt->close();
        if ($points_to_use <= $pointsRow['loyalty_points']) {
            $discount = floor($points_to_use / 100);
            $total_price = max(0, $total_price - $discount);
            $points_used = $points_to_use;
        }
    }
    
    // Check availability again (prevent double booking)
    $checkSql = "SELECT COUNT(*) as count FROM bookings b 
                 WHERE b.room_type_id = ? 
                 AND b.status IN ('confirmed', 'checked_in')
                 AND b.checkin_date < ? AND b.checkout_date > ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("iss", $room_type_id, $checkout, $checkin);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $row = $checkResult->fetch_assoc();
    $checkStmt->close();
    
    if ($row['count'] > 0) {
        $_SESSION['booking_error'] = "Sorry, this room type is no longer available for selected dates.";
        header("Location: ../views/guest/search_room.php");
        exit();
    }
    
    // Insert booking (room_id is NULL, will be assigned at check-in)
    $sql = "INSERT INTO bookings (guest_id, room_id, room_type_id, checkin_date, checkout_date, num_guests, total_price, loyalty_points_used, special_requests, status, source) 
            VALUES (?, NULL, ?, ?, ?, ?, ?, ?, ?, 'confirmed', 'online')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissiiis", $guest_id, $room_type_id, $checkin, $checkout, $guests, $total_price, $points_used, $special_requests);
    
    if ($stmt->execute()) {
        $booking_id = $stmt->insert_id;
        $stmt->close();
        
        // Deduct loyalty points from user if any were used
        if ($points_used > 0) {
            $deductSql = "UPDATE users SET loyalty_points = loyalty_points - ? WHERE id = ?";
            $deductStmt = $conn->prepare($deductSql);
            $deductStmt->bind_param("ii", $points_used, $guest_id);
            $deductStmt->execute();
            $deductStmt->close();
            // Update session points
            $_SESSION['loyalty_points'] -= $points_used;
        }
        
        // Create billing record
        $billSql = "INSERT INTO billing (booking_id, guest_id, base_amount, total_amount, payment_status) 
                    VALUES (?, ?, ?, ?, 'pending')";
        $billStmt = $conn->prepare($billSql);
        $billStmt->bind_param("iidd", $booking_id, $guest_id, $total_price, $total_price);
        $billStmt->execute();
        $billStmt->close();
        
        $_SESSION['booking_success'] = $booking_id;
        header("Location: ../views/guest/booking_confirmation.php");
        exit();
    } else {
        $_SESSION['booking_error'] = "Booking failed. Please try again.";
        header("Location: ../views/guest/search_room.php");
        exit();
    }
} else {
    header("Location: ../views/guest/search_room.php");
    exit();
}
$conn->close();
?>