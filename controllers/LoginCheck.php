<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hotel_booking_system";
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = "Please enter both email and password";
        header("Location: ../views/guest/login.php");
        exit();
    }
    
    // PREPARED STATEMENT
    $sql = "SELECT id, name, email, password_hash, role, is_active, loyalty_points FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user_data = $result->fetch_assoc();
        
        if (password_verify($password, $user_data['password_hash'])) {
            if ($user_data['is_active'] == 1) {
                $_SESSION['user_id'] = $user_data['id'];
                $_SESSION['user_name'] = $user_data['name'];
                $_SESSION['user_email'] = $user_data['email'];
                $_SESSION['role'] = $user_data['role'];
                $_SESSION['loyalty_points'] = $user_data['loyalty_points'];
                
                $stmt->close();
                $conn->close();
                header("Location: ../views/guest/dashboard.php");
                exit();
            } else {
                $_SESSION['login_error'] = "Account is deactivated";
            }
        } else {
            $_SESSION['login_error'] = "Invalid password";
        }
    } else {
        $_SESSION['login_error'] = "Email not found";
    }
    
    $stmt->close();
    $conn->close();
    header("Location: ../views/guest/login.php");
    exit();
    
} else {
    header("Location: ../views/guest/login.php");
    exit();
}
?>