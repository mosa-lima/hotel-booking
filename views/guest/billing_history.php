<!DOCTYPE html>
<html>
<head>
    <title>Billing History</title>
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

$guest_id = $_SESSION['user_id'];

$sql = "SELECT b.*, bk.id as booking_id, bk.checkin_date, bk.checkout_date, bk.status as booking_status, rt.name as room_type_name
        FROM billing b
        JOIN bookings bk ON b.booking_id = bk.id
        JOIN room_types rt ON bk.room_type_id = rt.id
        WHERE b.guest_id = ?
        ORDER BY b.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $guest_id);
$stmt->execute();
$result = $stmt->get_result();

$bills = [];
while ($row = $result->fetch_assoc()) {
    $bills[] = $row;
}
$stmt->close();
$conn->close();
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

<h1>Billing History</h1>

<?php if(count($bills) > 0): ?>
    <table>
        <tr>
            <th>Bill ID</th>
            <th>Booking ID</th>
            <th>Room Type</th>
            <th>Stay Dates</th>
            <th>Base Amount</th>
            <th>Extras</th>
            <th>Discount</th>
            <th>Total</th>
            <th>Payment Status</th>
            <th>Action</th>
        </tr>
        <?php foreach($bills as $bill): ?>
        <tr>
            <td>#<?php echo $bill['id']; ?></td>
            <td>#<?php echo $bill['booking_id']; ?></td>
            <td><?php echo $bill['room_type_name']; ?></td>
            <td><?php echo $bill['checkin_date']; ?> to <?php echo $bill['checkout_date']; ?></td>
            <td>$<?php echo number_format($bill['base_amount'], 2); ?></td>
            <td>$<?php echo number_format($bill['extras_amount'], 2); ?></td>
            <td>$<?php echo number_format($bill['discount_amount'], 2); ?></td>
            <td><strong>$<?php echo number_format($bill['total_amount'], 2); ?></strong></td>
            <td>
                <?php if($bill['payment_status'] == 'paid'): ?>
                    <span class="paid">Paid</span>
                <?php else: ?>
                    <span class="pending">Pending</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if($bill['payment_status'] == 'paid' && $bill['receipt_path']): ?>
                    <a href="../../<?php echo $bill['receipt_path']; ?>" target="_blank">View Receipt</a>
                <?php elseif($bill['payment_status'] == 'pending' && $bill['booking_status'] == 'checked_in'): ?>
                    <a href="make_payment.php?bill_id=<?php echo $bill['id']; ?>" style="color: blue;">Pay Now</a>
                <?php else: ?>
                    -
                <?php endif; ?>
             </td>
         </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p style="text-align: center;">No billing records found.</p>
<?php endif; ?>

</body>
</html>