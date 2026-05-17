<?php


class AdminModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getDashboardStats() {
        $stats = ['occupancy_rate' => 0, 'today_revenue' => 0.00, 'total_rooms' => 0, 'occupied_rooms' => 0, 'active_maintenance' => 0, 'pending_reviews' => 0];
        
        $res = $this->db->query("SELECT COUNT(*) AS total FROM rooms");
        if ($res && $row = $res->fetch_assoc()) $stats['total_rooms'] = $row['total'];

        $res = $this->db->query("SELECT COUNT(*) AS total FROM rooms WHERE status = 'occupied'");
        if ($res && $row = $res->fetch_assoc()) $stats['occupied_rooms'] = $row['total'];

        if ($stats['total_rooms'] > 0) {
            $stats['occupancy_rate'] = round(($stats['occupied_rooms'] / $stats['total_rooms']) * 100);
        }

        $res = $this->db->query("SELECT SUM(total_price) AS total FROM bookings WHERE DATE(created_at) = CURDATE() AND status != 'cancelled'");
        if ($res && $row = $res->fetch_assoc()) $stats['today_revenue'] = $row['total'] ?? 0.00;

        return $stats;
    }
    public function getAllBookings() {
        
        $res = $this->db->query("SELECT * FROM bookings ORDER BY id DESC");
        if (!$res) {
           
            return $this->db->query("SELECT 1 AS id, 'No Data' AS guest_name, 0.00 AS total_price, 'pending' AS status, NOW() AS created_at FROM DUAL WHERE 1=0");
        }
        return $res;
    }
    
    public function getAllGuests() {
        
        $res = $this->db->query("SELECT * FROM users WHERE role = 'guest' ORDER BY id DESC");
        if (!$res) {
            
            return $this->db->query("SELECT 1 AS id, 'No Data' AS name, '' AS email, 1 AS is_active FROM DUAL WHERE 1=0");
        }
        return $res;
    }

    public function getAllRoomTypes() {
        return $this->db->query("SELECT * FROM room_types ORDER BY id DESC");
    }

    public function getAllRooms() {
        return $this->db->query("SELECT r.*, rt.name AS room_type_name FROM rooms r LEFT JOIN room_types rt ON r.room_type_id = rt.id ORDER BY r.room_number ASC");
    }

   
    public function generateComprehensiveReports() {
        $base = 0;
        $res = $this->db->query("SELECT SUM(total_price) AS total FROM bookings WHERE status != 'cancelled'");
        if ($res && $row = $res->fetch_assoc()) $base = $row['total'] ?? 0;

        return [
            'extras' => ['base' => $base, 'service' => 0, 'discounts' => 0],
            'complaints' => ['volume' => 0, 'speed' => 0],
            'loyalty' => ['earned' => 0]
        ];
    }

    
    public function getReviewsAndSummaries() {
        $avg_clean = 0; $avg_serv = 0;
        $avg_res = $this->db->query("SELECT AVG(cleanliness_rating) AS clean, AVG(service_rating) AS serv FROM reviews");
        if ($avg_res && $row = $avg_res->fetch_assoc()) {
            $avg_clean = $row['clean'] ?? 0; $avg_serv = $row['serv'] ?? 0;
        }

        $list_res = $this->db->query("SELECT r.*, u.name AS guest_name FROM reviews r JOIN users u ON r.user_id = u.id ORDER BY r.id DESC");
        if (!$list_res) {
            $list_res = $this->db->query("SELECT *, 'Valued Guest' AS guest_name FROM reviews ORDER BY id DESC");
        }

        return [
            'avg' => ['clean' => $avg_clean, 'serv' => $avg_serv],
            'list' => $list_res
        ];
    }

    public function submitAdminReviewReply($reviewId, $replyText) {
        $stmt = $this->db->prepare("UPDATE reviews SET admin_reply = ? WHERE id = ?");
        $stmt->bind_param("si", $replyText, $reviewId);
        return $stmt->execute();
    }
}
?>