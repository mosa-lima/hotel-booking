<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>



<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guest'){
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hotel_booking_system";
$conn = new mysqli($host, $user, $pass, $dbname);

include "../../models/BookingModel.php";
$bookingModel = new BookingModel($conn);
$user_id = $_SESSION['user_id'];
$allBookings = $bookingModel->getAllBookings($user_id);

$conn->close();

$success = $_SESSION['cancel_success'] ?? '';
$error = $_SESSION['cancel_error'] ?? '';
unset($_SESSION['cancel_success']);
unset($_SESSION['cancel_error']);
?>

<div class="menu">
    <a href="dashboard.php">Dashboard</a>
    <a href="search_room.php">Search Rooms</a>
    <a href="my_bookings.php">My Bookings</a>
    <a href="profile.php">My Profile</a>
    <a href="service_request.php">Service Requests</a>
    <a href="billing_history.php">Billing</a>
    <a href="../../controllers/Logout.php">Logout</a>
</div>

<div class="container">
    <h1>My Bookings</h1>
    
    <?php if($success): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>
    <?php if($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <?php if(count($allBookings) > 0): ?>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>Room Type</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Guests</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach($allBookings as $booking): ?>
            <tr>
                <td>#<?php echo $booking['id']; ?></td>
                <td><?php echo $booking['room_type_name']; ?></td>
                <td><?php echo $booking['checkin_date']; ?></td>
                <td><?php echo $booking['checkout_date']; ?></td>
                <td><?php echo $booking['num_guests']; ?></td>
                <td>$<?php echo number_format($booking['total_price'], 2); ?></td>
                <td>
                    <span style="color: 
                        <?php 
                        if($booking['status'] == 'confirmed') echo 'green';
                        elseif($booking['status'] == 'cancelled') echo 'red';
                        elseif($booking['status'] == 'checked_in') echo 'blue';
                        elseif($booking['status'] == 'checked_out') echo 'gray';
                        else echo 'orange';
                        ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $booking['status'])); ?>
                    </span>
                </td>
                <td>
                    <a href="booking_details.php?id=<?php echo $booking['id']; ?>">View</a>
                    <?php if($booking['status'] == 'confirmed'): ?>
                        | <a href="../../controllers/CancelBooking.php?id=<?php echo $booking['id']; ?>" onclick="return confirm('Cancel this booking?')">Cancel</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No bookings found.</p>
    <?php endif; ?>
</div>

</body>
</html>