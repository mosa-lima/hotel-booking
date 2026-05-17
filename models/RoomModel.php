<?php
class RoomModel {
    private $conn;
    
    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }
    
    // Search available rooms with date range and guests - PREPARED STATEMENT
    public function searchAvailableRooms($checkin, $checkout, $guests) {
        $sql = "SELECT rt.*, 
                (SELECT COUNT(*) FROM rooms r WHERE r.room_type_id = rt.id AND r.status = 'available') as available_rooms
                FROM room_types rt 
                WHERE rt.max_capacity >= ? 
                AND rt.id NOT IN (
                    SELECT b.room_type_id FROM bookings b 
                    WHERE b.status IN ('confirmed', 'checked_in')
                    AND b.checkin_date < ? 
                    AND b.checkout_date > ?
                )";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iss", $guests, $checkout, $checkin);
        $stmt->execute();
        $result = $stmt->get_result();
        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $row['current_price'] = $this->getSeasonalPrice($row['id'], $checkin, $checkout, $row['price_per_night']);
            $rooms[] = $row;
        }
        $stmt->close();
        return $rooms;
    }
    
    // Get seasonal price for a room type - PREPARED STATEMENT
    public function getSeasonalPrice($room_type_id, $checkin, $checkout, $default_price) {
        $sql = "SELECT price_per_night FROM seasonal_pricing 
                WHERE room_type_id = ? 
                AND ((start_date BETWEEN ? AND ?) OR (end_date BETWEEN ? AND ?))";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issss", $room_type_id, $checkin, $checkout, $checkin, $checkout);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $stmt->close();
            return $row['price_per_night'];
        }
        $stmt->close();
        return $default_price;
    }
    
    // Get single room type by ID - PREPARED STATEMENT
    public function getRoomTypeById($id) {
        $sql = "SELECT * FROM room_types WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $row['amenities'] = json_decode($row['amenities'], true);
            $stmt->close();
            return $row;
        }
        $stmt->close();
        return null;
    }
    
    // Get all room types (for room details page)
    public function getAllRoomTypes() {
        $sql = "SELECT * FROM room_types";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $row['amenities'] = json_decode($row['amenities'], true);
            $rooms[] = $row;
        }
        $stmt->close();
        return $rooms;
    }
}
?>