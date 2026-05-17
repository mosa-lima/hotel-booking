<?php
class BookingModel {
    private $conn;
    
    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }
    
    // Get upcoming bookings for a guest - PREPARED STATEMENT
    public function getUpcomingBookings($guest_id) {
        $sql = "SELECT b.*, rt.name as room_type_name 
                FROM bookings b 
                JOIN room_types rt ON b.room_type_id = rt.id 
                WHERE b.guest_id = ? 
                AND b.checkout_date >= CURDATE() 
                AND b.status NOT IN ('cancelled', 'checked_out')
                ORDER BY b.checkin_date ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $guest_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $bookings = [];
        while($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        $stmt->close();
        return $bookings;
    }
    
    // Get past bookings for a guest - PREPARED STATEMENT
    public function getPastBookings($guest_id, $limit = 5) {
        $sql = "SELECT b.*, rt.name as room_type_name,
                (SELECT COUNT(*) FROM reviews WHERE booking_id = b.id) as has_review
                FROM bookings b 
                JOIN room_types rt ON b.room_type_id = rt.id 
                WHERE b.guest_id = ? 
                AND (b.checkout_date < CURDATE() OR b.status = 'checked_out')
                ORDER BY b.checkout_date DESC 
                LIMIT ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $guest_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $bookings = [];
        while($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        $stmt->close();
        return $bookings;
    }
    
    // Get all bookings for a guest - PREPARED STATEMENT
    public function getAllBookings($guest_id) {
        $sql = "SELECT b.*, rt.name as room_type_name,
                (SELECT COUNT(*) FROM reviews WHERE booking_id = b.id) as has_review
                FROM bookings b 
                JOIN room_types rt ON b.room_type_id = rt.id 
                WHERE b.guest_id = ? 
                ORDER BY b.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $guest_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $bookings = [];
        while($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        $stmt->close();
        return $bookings;
    }
    
    // Get single booking details - PREPARED STATEMENT
    public function getBookingById($booking_id, $guest_id) {
        $sql = "SELECT b.*, rt.name as room_type_name, rt.description, rt.amenities, 
                rt.price_per_night, r.room_number, r.floor
                FROM bookings b 
                JOIN room_types rt ON b.room_type_id = rt.id 
                LEFT JOIN rooms r ON b.room_id = r.id 
                WHERE b.id = ? AND b.guest_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $booking_id, $guest_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $booking = $result->fetch_assoc();
            $booking['amenities'] = json_decode($booking['amenities'], true);
            $stmt->close();
            return $booking;
        }
        $stmt->close();
        return null;
    }
    
    // Cancel booking - PREPARED STATEMENT
    public function cancelBooking($booking_id, $guest_id) {
        // First check if cancellation is allowed (at least 2 days before check-in)
        $checkSql = "SELECT checkin_date, loyalty_points_used FROM bookings WHERE id = ? AND guest_id = ?";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->bind_param("ii", $booking_id, $guest_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $booking = $result->fetch_assoc();
        $checkStmt->close();
        
        if (!$booking) {
            return ["success" => false, "message" => "Booking not found"];
        }
        
        $checkin = new DateTime($booking['checkin_date']);
        $today = new DateTime();
        $diff = $today->diff($checkin)->days;
        
        if ($checkin <= $today) {
            return ["success" => false, "message" => "Cannot cancel past or current bookings"];
        }
        
        if ($diff < 2) {
            return ["success" => false, "message" => "Cancellation must be at least 2 days before check-in date"];
        }
        
        // Update booking status
        $sql = "UPDATE bookings SET status = 'cancelled' WHERE id = ? AND guest_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $booking_id, $guest_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            if ($booking['loyalty_points_used'] > 0) {
                return ["success" => true, "message" => "Booking cancelled. Loyalty points refunded.", "points_refund" => $booking['loyalty_points_used']];
            }
            return ["success" => true, "message" => "Booking cancelled successfully"];
        } else {
            $error = $stmt->error;
            $stmt->close();
            return ["success" => false, "message" => "Cancellation failed: " . $error];
        }
    }
    
    // Get booking count by status - PREPARED STATEMENT
    public function getBookingCountByStatus($guest_id, $status) {
        $sql = "SELECT COUNT(*) as count FROM bookings WHERE guest_id = ? AND status = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $guest_id, $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'];
    }
    
    // Check if guest has completed stays
    public function hasCompletedStays($guest_id) {
        $sql = "SELECT COUNT(*) as count FROM bookings 
                WHERE guest_id = ? AND (checkout_date < CURDATE() OR status = 'checked_out')
                AND status != 'cancelled'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $guest_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'] > 0;
    }
}
?>