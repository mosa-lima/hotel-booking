<!DOCTYPE html>
<html>
<head>
    <title>Write a Review</title>
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

$booking_id = $_GET['booking_id'] ?? 0;
$guest_id = $_SESSION['user_id'];

if (!$booking_id) {
    header("Location: my_bookings.php");
    exit();
}

// Verify booking belongs to guest and is completed
$checkSql = "SELECT b.*, rt.name as room_type_name 
             FROM bookings b 
             JOIN room_types rt ON b.room_type_id = rt.id 
             WHERE b.id = ? AND b.guest_id = ? 
             AND (b.checkout_date < CURDATE() OR b.status = 'checked_out')";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("ii", $booking_id, $guest_id);
$checkStmt->execute();
$result = $checkStmt->get_result();
$booking = $result->fetch_assoc();
$checkStmt->close();

if (!$booking) {
    header("Location: my_bookings.php");
    exit();
}

// Check if already reviewed
include "../../models/ReviewModel.php";
$reviewModel = new ReviewModel($conn);
$existing_review = $reviewModel->getReviewByBooking($booking_id, $guest_id);
$conn->close();

$success = $_SESSION['review_success'] ?? '';
$error = $_SESSION['review_error'] ?? '';
unset($_SESSION['review_success']);
unset($_SESSION['review_error']);
?>

<div class="menu" style="background-color: cornflowerblue; padding:15px; text-align:center; margin-bottom:20px;">
    <a href="dashboard.php">Dashboard</a>
    <a href="search_room.php">Search Rooms</a>
    <a href="my_bookings.php">My Bookings</a>
    <a href="profile.php">My Profile</a>
    <a href="service_request.php">Service Requests</a>
    <a href="billing_history.php">Billing</a>
    <a href="../../controllers/Logout.php">Logout</a>
</div>

<h1><?php echo $existing_review ? 'Edit Your Review' : 'Write a Review'; ?></h1>
<p style="text-align: center;">For: <?php echo $booking['room_type_name']; ?> (Stay: <?php echo $booking['checkin_date']; ?> to <?php echo $booking['checkout_date']; ?>)</p>

<?php if($success): ?>
    <p class="success"><?php echo $success; ?></p>
<?php endif; ?>
<?php if($error): ?>
    <p class="error"><?php echo $error; ?></p>
<?php endif; ?>

<form action="../../controllers/AddReview.php" method="post">
    <table>
        <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
        <?php if($existing_review): ?>
            <input type="hidden" name="review_id" value="<?php echo $existing_review['id']; ?>">
        <?php endif; ?>
        <tr>
            <td>Overall Rating (1-5): </td>
            <td>
                <select name="overall_rating" required>
                    <option value="">Select</option>
                    <?php for($i=1; $i<=5; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo ($existing_review && $existing_review['overall_rating'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?> star<?php echo $i>1 ? 's' : ''; ?></option>
                    <?php endfor; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Cleanliness Rating (1-5): </td>
            <td>
                <select name="cleanliness_rating" required>
                    <option value="">Select</option>
                    <?php for($i=1; $i<=5; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo ($existing_review && $existing_review['cleanliness_rating'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?> star<?php echo $i>1 ? 's' : ''; ?></option>
                    <?php endfor; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Service Rating (1-5): </td>
            <td>
                <select name="service_rating" required>
                    <option value="">Select</option>
                    <?php for($i=1; $i<=5; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo ($existing_review && $existing_review['service_rating'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?> star<?php echo $i>1 ? 's' : ''; ?></option>
                    <?php endfor; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Your Review: </td>
            <td><textarea name="review_text" rows="5" cols="40" required><?php echo $existing_review ? htmlspecialchars($existing_review['review_text']) : ''; ?></textarea> </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="<?php echo $existing_review ? 'Update Review' : 'Submit Review'; ?>">
                <?php if($existing_review): ?>
                    <a href="../../controllers/DeleteReview.php?id=<?php echo $existing_review['id']; ?>&booking_id=<?php echo $booking_id; ?>" onclick="return confirm('Delete this review?')" style="color:red;">Delete Review</a>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</form>

<p style="text-align: center;"><a href="my_bookings.php">Back to My Bookings</a></p>

</body>
</html>