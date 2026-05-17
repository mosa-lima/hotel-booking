<!DOCTYPE html>
<html>
<head>
    <title>Book Room</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>



<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guest'){
    header("Location: login.php");
    exit();
}

// Get data from GET or POST
$room_type_id = $_GET['room_type_id'] ?? $_POST['room_type_id'] ?? 0;
$checkin = $_GET['checkin'] ?? $_POST['checkin'] ?? '';
$checkout = $_GET['checkout'] ?? $_POST['checkout'] ?? '';
$guests = $_GET['guests'] ?? $_POST['guests'] ?? 1;
$price_per_night = $_GET['price'] ?? $_POST['price'] ?? 0;

if (!$room_type_id || !$checkin || !$checkout) {
    header("Location: search_room.php");
    exit();
}

// Calculate total
$date1 = new DateTime($checkin);
$date2 = new DateTime($checkout);
$nights = $date1->diff($date2)->days;
$total = $nights * $price_per_night;
?>

<h1>Confirm Booking</h1>

<form action="../../controllers/BookRoom.php" method="post">
    <table>
        <tr>
            <td>Room Type: </td>
            <td><?php echo htmlspecialchars($room_type_id); ?> </td>
        </tr>
        <tr>
            <td>Check-in: </td>
            <td><?php echo $checkin; ?> </td>
        </tr>
        <tr>
            <td>Check-out: </td>
            <td><?php echo $checkout; ?> </td>
        </tr>
        <tr>
            <td>Nights: </td>
            <td><?php echo $nights; ?> </td>
        </tr>
        <tr>
            <td>Price per night: </td>
            <td>$<?php echo number_format($price_per_night, 2); ?> </td>
        </tr>
        <tr>
            <td>Total Price: </td>
            <td><strong>$<?php echo number_format($total, 2); ?></strong> </td>
        </tr>
        <tr>
            <td>Special Requests: </td>
            <td><textarea name="special_requests" rows="3" cols="30"></textarea> </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="room_type_id" value="<?php echo $room_type_id; ?>">
                <input type="hidden" name="checkin" value="<?php echo $checkin; ?>">
                <input type="hidden" name="checkout" value="<?php echo $checkout; ?>">
                <input type="hidden" name="guests" value="<?php echo $guests; ?>">
                <input type="hidden" name="price" value="<?php echo $price_per_night; ?>">
                <input type="submit" value="Confirm Booking">
            </td>
        </tr>
    </table>
</form>

<p style="text-align: center;"><a href="search_room.php">Back to Search</a></p>

</body>
</html>