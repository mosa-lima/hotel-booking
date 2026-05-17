<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guest') {
    header("Location: login.php");
    exit();
}

$booking_id = $_GET['id'] ?? 0;
if (!$booking_id) {
    header("Location: my_bookings.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hotel_booking_system";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT b.*, rt.name as room_type_name, rt.description, rt.amenities, 
        rt.price_per_night, r.room_number, r.floor
        FROM bookings b 
        JOIN room_types rt ON b.room_type_id = rt.id 
        LEFT JOIN rooms r ON b.room_id = r.id 
        WHERE b.id = ? AND b.guest_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$booking) {
    header("Location: my_bookings.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booking Details</title>
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
    <h1>Booking Details #<?php echo $booking['id']; ?></h1>
    <table>
        <tr><th>Room Type</th><td><?php echo htmlspecialchars($booking['room_type_name']); ?></td></tr>
        <tr><th>Description</th><td><?php echo htmlspecialchars($booking['description']); ?></td></tr>
        <tr><th>Check-in</th><td><?php echo $booking['checkin_date']; ?></td></tr>
        <tr><th>Check-out</th><td><?php echo $booking['checkout_date']; ?></td></tr>
        <tr><th>Guests</th><td><?php echo $booking['num_guests']; ?></td></tr>
        <tr><th>Total Price</th><td>$<?php echo number_format($booking['total_price'], 2); ?></td></tr>
        <tr><th>Room Number</th><td><?php echo $booking['room_number'] ?? 'Not assigned yet'; ?></td></tr>
        <tr><th>Status</th><td class="status-<?php echo $booking['status']; ?>"><?php echo ucfirst(str_replace('_',' ',$booking['status'])); ?></td></tr>
        <tr><th>Special Requests</th><td><?php echo nl2br(htmlspecialchars($booking['special_requests'])); ?></td></tr>
    </table>
    <p style="text-align:center;"><a href="my_bookings.php">Back to My Bookings</a></p>
</div>
</body>
</html>