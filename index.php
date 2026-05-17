<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);


$db = new mysqli("localhost", "root", "", "hotel_management");
if ($db->connect_error) {
    die("Database connection failure: " . $db->connect_error);
}


require_once 'models/AdminModel.php';
require_once 'controllers/AdminController.php';

$model = new AdminModel($db);
$controller = new AdminController($model);


$action = $_GET['action'] ?? 'dashboard';


switch ($action) {
    case 'dashboard':
        $controller->index();
        break;
        
    case 'room_types':
        $controller->viewRoomTypes();
        break;
        
    case 'rooms':
        $controller->viewPhysicalRooms();
        break;
        
    case 'pricing':
        $controller->viewPricing();
        break;

    case 'staff':
        $controller->viewStaff();
        break;

    case 'guests':
        $controller->viewGuests();
        break;

    case 'bookings':
        $controller->viewBookings();
        break;

    case 'reports':
        $controller->viewFinancialAndOccupancyReports();
        break;
        
    case 'reviews':
        $controller->viewAndRespondReviews();
        break;

    case 'announcements':
        $controller->viewAnnouncements();
        break;

    case 'logout':
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        session_destroy();
        header("Location: index.php?action=dashboard");
        exit();
        break;
        
    default:
        $controller->index();
        break;
}
?>