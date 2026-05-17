<?php


class AdminController {
    private $model;

    public function __construct($adminModel) {
        $this->model = $adminModel;
        if (session_status() === PHP_SESSION_NONE) { 
            session_start(); 
        }
    }

    
    private function renderView($viewFile, $data = [], $pageTitle = "Admin Panel") {
        extract($data);
        include 'views/layout/header.php'; 
        include "views/admin/{$viewFile}.php";
        include 'views/layout/footer.php';
    }

    public function index() {
        $this->renderView('dashboard', ['stats' => $this->model->getDashboardStats()], "Dashboard");
    }

    public function viewRoomTypes() {
        $this->renderView('room_types', ['roomTypes' => $this->model->getAllRoomTypes()], "Room Blueprints");
    }

    public function viewPhysicalRooms() {
        $this->renderView('rooms', ['rooms' => $this->model->getAllRooms(), 'roomTypes' => $this->model->getAllRoomTypes()], "Physical Inventory");
    }

    public function viewPricing() { 
        $this->renderView('pricing', ['roomTypes' => $this->model->getAllRoomTypes()], "Pricing Overrides"); 
    }

    public function viewStaff() { 
        $this->renderView('staff', [], "Manage Staff"); 
    }

   
    public function viewGuests() { 
        $guestsData = $this->model->getAllGuests();
        $this->renderView('guests', ['guests' => $guestsData], "Guests Ledger"); 
    }

   
    public function viewBookings() { 
        $bookingsData = $this->model->getAllBookings();
        $this->renderView('bookings', ['bookings' => $bookingsData], "Bookings Matrix"); 
    }

    public function viewAnnouncements() { 
        $this->renderView('announcements', [], "Global Notices"); 
    }

    public function viewFinancialAndOccupancyReports() {
        $reports = $this->model->generateComprehensiveReports();
        $this->renderView('reports', ['reports' => $reports], "Audit Summaries");
    }

    public function viewAndRespondReviews() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reply'])) {
            $this->model->submitAdminReviewReply(intval($_POST['review_id']), trim($_POST['admin_reply']));
            header("Location: index.php?action=reviews");
            exit();
        }
        $reviews = $this->model->getReviewsAndSummaries();
        $this->renderView('reviews', ['reviews' => $reviews], "Feedback Reviews");
    }
}
?>