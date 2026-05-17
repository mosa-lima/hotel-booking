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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'] ?? 0;
    $guest_id = $_SESSION['user_id'];
    $overall_rating = $_POST['overall_rating'] ?? 0;
    $cleanliness_rating = $_POST['cleanliness_rating'] ?? 0;
    $service_rating = $_POST['service_rating'] ?? 0;
    $review_text = trim($_POST['review_text'] ?? '');
    $review_id = $_POST['review_id'] ?? 0;
    
    // Validation
    $error = "";
    if ($overall_rating < 1 || $overall_rating > 5) $error = "Overall rating must be 1-5";
    elseif ($cleanliness_rating < 1 || $cleanliness_rating > 5) $error = "Cleanliness rating must be 1-5";
    elseif ($service_rating < 1 || $service_rating > 5) $error = "Service rating must be 1-5";
    elseif (empty($review_text)) $error = "Please write a review";
    
    if ($error) {
        $_SESSION['review_error'] = $error;
        header("Location: ../views/guest/review.php?booking_id=" . $booking_id);
        exit();
    }
    
    if ($review_id > 0) {
        // Update existing review
        if ($reviewModel->updateReview($review_id, $overall_rating, $cleanliness_rating, $service_rating, $review_text)) {
            $_SESSION['review_success'] = "Review updated successfully";
        } else {
            $_SESSION['review_error'] = "Failed to update review";
        }
    } else {
        // Add new review
        if (!$reviewModel->hasReviewed($booking_id, $guest_id)) {
            if ($reviewModel->addReview($booking_id, $guest_id, $overall_rating, $cleanliness_rating, $service_rating, $review_text)) {
                $_SESSION['review_success'] = "Thank you for your review!";
            } else {
                $_SESSION['review_error'] = "Failed to submit review";
            }
        } else {
            $_SESSION['review_error'] = "You have already reviewed this booking";
        }
    }
    
    $conn->close();
    header("Location: ../views/guest/review.php?booking_id=" . $booking_id);
    exit();
}
?>