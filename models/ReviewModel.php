<?php
class ReviewModel {
    private $conn;
    
    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }
    
    // Check if user already reviewed a booking - PREPARED STATEMENT
    public function hasReviewed($booking_id, $guest_id) {
        $sql = "SELECT id FROM reviews WHERE booking_id = ? AND guest_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $booking_id, $guest_id);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;
        $stmt->close();
        return $count > 0;
    }
    
    // Add a new review - PREPARED STATEMENT
    public function addReview($booking_id, $guest_id, $overall_rating, $cleanliness_rating, $service_rating, $review_text) {
        $sql = "INSERT INTO reviews (booking_id, guest_id, overall_rating, cleanliness_rating, service_rating, review_text) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiiiis", $booking_id, $guest_id, $overall_rating, $cleanliness_rating, $service_rating, $review_text);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    // Get review by booking ID - PREPARED STATEMENT
    public function getReviewByBooking($booking_id, $guest_id) {
        $sql = "SELECT * FROM reviews WHERE booking_id = ? AND guest_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $booking_id, $guest_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $stmt->close();
            return $row;
        }
        $stmt->close();
        return null;
    }
    
    // Update review - PREPARED STATEMENT
    public function updateReview($review_id, $overall_rating, $cleanliness_rating, $service_rating, $review_text) {
        $sql = "UPDATE reviews SET overall_rating = ?, cleanliness_rating = ?, service_rating = ?, review_text = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiisi", $overall_rating, $cleanliness_rating, $service_rating, $review_text, $review_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    // Delete review - PREPARED STATEMENT
    public function deleteReview($review_id, $guest_id) {
        $sql = "DELETE FROM reviews WHERE id = ? AND guest_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $review_id, $guest_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    // Get average ratings for a room type
    public function getRoomTypeRatings($room_type_id) {
        $sql = "SELECT 
                AVG(overall_rating) as avg_overall,
                AVG(cleanliness_rating) as avg_cleanliness,
                AVG(service_rating) as avg_service,
                COUNT(*) as total_reviews
                FROM reviews r
                JOIN bookings b ON r.booking_id = b.id
                WHERE b.room_type_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $room_type_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }
}
?>