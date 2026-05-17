<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>



<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guest'){
    header("Location: login.php");
    exit();
}

$booking_id = $_SESSION['booking_success'] ?? 0;
unset($_SESSION['booking_success']);

if (!$booking_id) {
    header("Location: dashboard.php");
    exit();
}

// Fetch booking details
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hotel_booking_system";
$conn = new mysqli($host, $user, $pass, $dbname);

$sql = "SELECT b.*, rt.name as room_type_name 
        FROM bookings b 
        JOIN room_types rt ON b.room_type_id = rt.id 
        WHERE b.id = ? AND b.guest_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<h1>Booking Confirmed!</h1>

<p class="success">Your booking has been successfully confirmed.</p>

<table>
    <tr>
        <td>Booking ID: </td>
        <td>#<?php echo $booking['id']; ?> </td>
    </tr>
    <tr>
        <td>Room Type: </td>
        <td><?php echo $booking['room_type_name']; ?> </td>
    </tr>
    <tr>
        <td>Check-in Date: </td>
        <td><?php echo $booking['checkin_date']; ?> </td>
    </tr>
    <tr>
        <td>Check-out Date: </td>
        <td><?php echo $booking['checkout_date']; ?> </td>
    </tr>
    <tr>
        <td>Total Price: </td>
        <td>$<?php echo number_format($booking['total_price'], 2); ?> </td>
    </tr>
    <tr>
        <td>Status: </td>
        <td><?php echo $booking['status']; ?> </td>
    </tr>
    <tr>
        <td>Special Requests: </td>
        <td><?php echo nl2br($booking['special_requests']); ?> </td>
    </tr>
</table>

<p style="text-align: center;">
    <a href="dashboard.php">Go to Dashboard</a> | 
    <a href="my_bookings.php">View My Bookings</a>
</p>

</body>
</html>