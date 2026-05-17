<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guest') {
    header("Location: login.php");
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

$user_id = $_SESSION['user_id'];

// Upcoming bookings
$upcomingSql = "SELECT b.*, rt.name as room_type_name 
                FROM bookings b 
                JOIN room_types rt ON b.room_type_id = rt.id 
                WHERE b.guest_id = ? AND b.checkout_date >= CURDATE() 
                AND b.status NOT IN ('cancelled', 'checked_out')
                ORDER BY b.checkin_date ASC";
$upcomingStmt = $conn->prepare($upcomingSql);
$upcomingStmt->bind_param("i", $user_id);
$upcomingStmt->execute();
$upcomingResult = $upcomingStmt->get_result();

// Past bookings
$pastSql = "SELECT b.*, rt.name as room_type_name,
            (SELECT COUNT(*) FROM reviews WHERE booking_id = b.id) as has_review
            FROM bookings b 
            JOIN room_types rt ON b.room_type_id = rt.id 
            WHERE b.guest_id = ? AND (b.checkout_date < CURDATE() OR b.status = 'checked_out')
            ORDER BY b.checkout_date DESC LIMIT 5";
$pastStmt = $conn->prepare($pastSql);
$pastStmt->bind_param("i", $user_id);
$pastStmt->execute();
$pastResult = $pastStmt->get_result();

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Guest Dashboard</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

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
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <div class="points-box">
        ⭐ Loyalty Points: <strong><?php echo (int)$_SESSION['loyalty_points']; ?></strong>
    </div>

    <h2>Upcoming Bookings</h2>
    <?php if ($upcomingResult->num_rows > 0): ?>
        <table>
            <tr>
                <th>Booking ID</th><th>Room Type</th><th>Check-in</th><th>Check-out</th><th>Guests</th><th>Total Price</th><th>Status</th><th>Action</th>
            </tr>
            <?php while ($booking = $upcomingResult->fetch_assoc()): ?>
            <tr>
                <td>#<?php echo $booking['id']; ?></td>
                <td><?php echo htmlspecialchars($booking['room_type_name']); ?></td>
                <td><?php echo $booking['checkin_date']; ?></td>
                <td><?php echo $booking['checkout_date']; ?></td>
                <td><?php echo $booking['num_guests']; ?></td>
                <td>$<?php echo number_format($booking['total_price'], 2); ?></td>
                <td class="status-<?php echo $booking['status']; ?>"><?php echo ucfirst(str_replace('_', ' ', $booking['status'])); ?></td>
                <td><a href="booking_details.php?id=<?php echo $booking['id']; ?>">View</a>
                <?php if ($booking['status'] == 'confirmed'): ?>
                    | <a href="../../controllers/CancelBooking.php?id=<?php echo $booking['id']; ?>" onclick="return confirm('Cancel booking?')">Cancel</a>
                <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">No upcoming bookings. <a href="search_room.php">Book a room now!</a></p>
    <?php endif; ?>

    <h2>Recent Completed Stays</h2>
    <?php if ($pastResult->num_rows > 0): ?>
        <table>
            <tr><th>Booking ID</th><th>Room Type</th><th>Check-in</th><th>Check-out</th><th>Total Price</th><th>Review</th></tr>
            <?php while ($booking = $pastResult->fetch_assoc()): ?>
            <tr>
                <td>#<?php echo $booking['id']; ?></td>
                <td><?php echo htmlspecialchars($booking['room_type_name']); ?></td>
                <td><?php echo $booking['checkin_date']; ?></td>
                <td><?php echo $booking['checkout_date']; ?></td>
                <td>$<?php echo number_format($booking['total_price'], 2); ?></td>
                <td><?php echo $booking['has_review'] ? '✓ Reviewed' : '<a href="review.php?booking_id='.$booking['id'].'">Write Review</a>'; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">No completed stays yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
<?php $upcomingStmt->close(); $pastStmt->close(); ?>