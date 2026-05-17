<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hotel_booking_system";
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit();
}

$checkin = $_GET['checkin'] ?? '';
$checkout = $_GET['checkout'] ?? '';
$guests = $_GET['guests'] ?? 1;

if (empty($checkin) || empty($checkout)) {
    echo json_encode(['success' => false, 'message' => 'Please select dates']);
    exit();
}

// Prepared statement
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
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $guests, $checkout, $checkin);
$stmt->execute();
$result = $stmt->get_result();

$rooms = [];
while ($row = $result->fetch_assoc()) {
    // Get seasonal price
    $price = getSeasonalPrice($conn, $row['id'], $checkin, $checkout, $row['price_per_night']);
    $row['current_price'] = $price;
    $rooms[] = $row;
}
$stmt->close();
$conn->close();

echo json_encode(['success' => true, 'rooms' => $rooms]);

function getSeasonalPrice($conn, $room_type_id, $checkin, $checkout, $default_price) {
    $sql = "SELECT price_per_night FROM seasonal_pricing 
            WHERE room_type_id = ? 
            AND ((start_date BETWEEN ? AND ?) OR (end_date BETWEEN ? AND ?))";
    $stmt = $conn->prepare($sql);
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
?>