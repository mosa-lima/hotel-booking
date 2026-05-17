<?php
class ServiceModel {
    private $conn;
    
    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }
    
    // Add a service request - PREPARED STATEMENT
    public function addServiceRequest($booking_id, $guest_id, $room_id, $service_type, $description) {
        $sql = "INSERT INTO service_requests (booking_id, guest_id, room_id, service_type, description, status) 
                VALUES (?, ?, ?, ?, ?, 'pending')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiiss", $booking_id, $guest_id, $room_id, $service_type, $description);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    // Get all service requests for a guest - PREPARED STATEMENT
    public function getGuestServiceRequests($guest_id) {
        $sql = "SELECT sr.*, b.room_type_id, rt.name as room_type_name, r.room_number
                FROM service_requests sr
                JOIN bookings b ON sr.booking_id = b.id
                JOIN room_types rt ON b.room_type_id = rt.id
                LEFT JOIN rooms r ON sr.room_id = r.id
                WHERE sr.guest_id = ?
                ORDER BY sr.requested_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $guest_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $requests = [];
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
        $stmt->close();
        return $requests;
    }
    
    // Get active bookings where guest can request services (checked in, not past checkout)
    public function getActiveBookingsForServices($guest_id) {
        $sql = "SELECT b.id, b.checkin_date, b.checkout_date, rt.name as room_type_name, r.room_number
                FROM bookings b
                JOIN room_types rt ON b.room_type_id = rt.id
                LEFT JOIN rooms r ON b.room_id = r.id
                WHERE b.guest_id = ? AND b.status = 'checked_in'
                ORDER BY b.checkout_date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $guest_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        $stmt->close();
        return $bookings;
    }
}
?>