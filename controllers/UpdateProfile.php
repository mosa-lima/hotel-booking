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

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $name = test_input($_POST['name']);
    $phone = test_input($_POST['phone']);
    $nationality = test_input($_POST['nationality'] ?? '');
    $id_number = test_input($_POST['id_number'] ?? '');
    
    // Validation
    $error = "";
    if (empty($name)) $error = "Name is required";
    elseif (empty($phone)) $error = "Phone is required";
    elseif (!preg_match("/^[0-9]{11}$/", $phone)) $error = "Phone must be 11 digits";
    
    if ($error) {
        $_SESSION['profile_error'] = $error;
        header("Location: ../views/guest/profile.php");
        exit();
    }
    
    // Update profile
    $sql = "UPDATE users SET name = ?, phone = ?, nationality = ?, id_number = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $phone, $nationality, $id_number, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['user_name'] = $name;
        $_SESSION['profile_success'] = "Profile updated successfully";
    } else {
        $_SESSION['profile_error'] = "Update failed";
    }
    $stmt->close();
    
    // Handle profile picture upload
    if ($_FILES['profile_pic']['error'] == 0) {
        $target_dir = "../uploads/profiles/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_name = time() . "_" . basename($_FILES['profile_pic']['name']);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
                $picSql = "UPDATE users SET profile_pic = ? WHERE id = ?";
                $picStmt = $conn->prepare($picSql);
                $picPath = "uploads/profiles/" . $file_name;
                $picStmt->bind_param("si", $picPath, $user_id);
                $picStmt->execute();
                $picStmt->close();
            }
        }
    }
    
    $conn->close();
    header("Location: ../views/guest/profile.php");
    exit();
}
?>