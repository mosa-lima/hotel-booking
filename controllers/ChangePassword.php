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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $error = "";
    if (empty($old_password)) $error = "Old password required";
    elseif (strlen($new_password) < 6) $error = "New password must be at least 6 characters";
    elseif ($new_password != $confirm_password) $error = "Passwords do not match";
    
    if ($error) {
        $_SESSION['profile_error'] = $error;
        header("Location: ../views/guest/profile.php");
        exit();
    }
    
    // Verify old password
    $sql = "SELECT password_hash FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if (password_verify($old_password, $user['password_hash'])) {
        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $updateSql = "UPDATE users SET password_hash = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $new_hash, $user_id);
        if ($updateStmt->execute()) {
            $_SESSION['profile_success'] = "Password changed successfully";
        } else {
            $_SESSION['profile_error'] = "Failed to change password";
        }
        $updateStmt->close();
    } else {
        $_SESSION['profile_error'] = "Old password is incorrect";
    }
    
    $conn->close();
    header("Location: ../views/guest/profile.php");
    exit();
}
?>