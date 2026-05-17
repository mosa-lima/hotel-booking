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

include "../models/ReviewModel.php";
$reviewModel = new ReviewModel($conn);

$review_id = $_GET['id'] ?? 0;
$booking_id = $_GET['booking_id'] ?? 0;
$guest_id = $_SESSION['user_id'];

if ($review_id > 0) {
    if ($reviewModel->deleteReview($review_id, $guest_id)) {
        $_SESSION['review_success'] = "Review deleted successfully";
    } else {
        $_SESSION['review_error'] = "Failed to delete review";
    }
}

$conn->close();
header("Location: ../views/guest/review.php?booking_id=" . $booking_id);
exit();
?>