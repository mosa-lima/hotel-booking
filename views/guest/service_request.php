<!DOCTYPE html>
<html>
<head>
    <title>Service Requests</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>



<?php
session_start();
echo "Your user ID: " . $_SESSION['user_id'] . "<br>";
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guest'){
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hotel_booking_system";
$conn = new mysqli($host, $user, $pass, $dbname);

include "../../models/ServiceModel.php";
$serviceModel = new ServiceModel($conn);

$guest_id = $_SESSION['user_id'];
$activeBookings = $serviceModel->getActiveBookingsForServices($guest_id);

$serviceRequests = $serviceModel->getGuestServiceRequests($guest_id);

$conn->close();

$success = $_SESSION['service_success'] ?? '';
$error = $_SESSION['service_error'] ?? '';
unset($_SESSION['service_success']);
unset($_SESSION['service_error']);
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

<h1>Service Requests</h1>

<?php if($success): ?>
    <p class="success"><?php echo $success; ?></p>
<?php endif; ?>
<?php if($error): ?>
    <p class="error"><?php echo $error; ?></p>
<?php endif; ?>

<h2>Request a Service</h2>
<form action="../../controllers/ServiceRequest.php" method="post">
    <table>
        <tr>
            <td>Select Active Stay: </td>
            <td>
                <select name="booking_id" required>
                    <option value="">Select a booking</option>
                    <?php foreach($activeBookings as $booking): ?>
                        <option value="<?php echo $booking['id']; ?>">
                            <?php echo $booking['room_type_name']; ?> (Room: <?php echo $booking['room_number'] ?? 'Not assigned'; ?>) - <?php echo $booking['checkin_date']; ?> to <?php echo $booking['checkout_date']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Service Type: </td>
            <td>
                <select name="service_type" required>
                    <option value="">Select type</option>
                    <option value="extra_bed">Extra Bed</option>
                    <option value="toiletries">Toiletries</option>
                    <option value="laundry">Laundry</option>
                    <option value="room_service">Room Service</option>
                    <option value="other">Other</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Description: </td>
            <td><textarea name="description" rows="3" cols="40" required></textarea> </td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Submit Request"></td>
        </tr>
    </table>
</form>

<h2>My Service Request History</h2>
<?php if(count($serviceRequests) > 0): ?>
    <table>
        <tr>
            <th>Request ID</th>
            <th>Booking</th>
            <th>Service Type</th>
            <th>Description</th>
            <th>Status</th>
            <th>Requested On</th>
        </tr>
        <?php foreach($serviceRequests as $req): ?>
        <tr>
            <td>#<?php echo $req['id']; ?></td>
            <td><?php echo $req['room_type_name']; ?></td>
            <td><?php echo ucfirst(str_replace('_', ' ', $req['service_type'])); ?></td>
            <td><?php echo htmlspecialchars($req['description']); ?></td>
            <td>
                <?php if($req['status'] == 'pending'): ?>
                    <span style="color: orange;">Pending</span>
                <?php elseif($req['status'] == 'in_progress'): ?>
                    <span style="color: blue;">In Progress</span>
                <?php else: ?>
                    <span style="color: green;">Completed</span>
                <?php endif; ?>
            </td>
            <td><?php echo $req['requested_at']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p style="text-align: center;">No service requests yet.</p>
<?php endif; ?>

</body>
</html>