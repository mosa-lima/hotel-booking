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

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// variables
$name = $email = $phone = $nationality = $id_number = "";
$nameErr = $emailErr = $passErr = $phoneErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // name validation
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";
        }
    }
    
    // email validation
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format (must contain @)";
        }
    }
    
    // password validation
    if (empty($_POST["password"])) {
        $passErr = "Password is required";
    } else {
        $password = $_POST["password"];
        if (strlen($password) < 6) {
            $passErr = "Password must be at least 6 characters";
        }
    }
    
    // confirm password
    $confirm_password = $_POST["confirm_password"] ?? "";
    if (empty($passErr) && $password != $confirm_password) {
        $passErr = "Passwords do not match";
    }
    
    // phone validation
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match("/^[0-9]{11}$/", $phone)) {
            $phoneErr = "Phone number must be exactly 11 digits";
        }
    }
    
    // optional fields
    $nationality = test_input($_POST["nationality"] ?? "");
    $id_number = test_input($_POST["id_number"] ?? "");
    
    // if no errors, proceed
    if (empty($nameErr) && empty($emailErr) && empty($passErr) && empty($phoneErr)) {
        
        // check if email already exists
        $checkSql = "SELECT id FROM users WHERE email = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();
        
        if ($checkStmt->num_rows > 0) {
            $emailErr = "Email already registered";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (name, email, password_hash, phone, nationality, id_number, role, loyalty_points, is_active) 
                    VALUES (?, ?, ?, ?, ?, ?, 'guest', 0, 1)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $name, $email, $hashed_password, $phone, $nationality, $id_number);
            
            if ($stmt->execute()) {
                $_SESSION['register_success'] = "Registration successful! Please login.";
                $stmt->close();
                $checkStmt->close();
                $conn->close();
                header("Location: ../views/guest/login.php");
                exit();
            } else {
                $emailErr = "Registration failed. Please try again.";
            }
            $stmt->close();
        }
        $checkStmt->close();
    }
    
    // If errors, store in session and go back
    $error_message = "";
    if ($nameErr) $error_message .= $nameErr . "\\n";
    if ($emailErr) $error_message .= $emailErr . "\\n";
    if ($passErr) $error_message .= $passErr . "\\n";
    if ($phoneErr) $error_message .= $phoneErr . "\\n";
    
    $_SESSION['register_error'] = nl2br($error_message);
    $_SESSION['old_name'] = $name;
    $_SESSION['old_email'] = $email;
    $_SESSION['old_phone'] = $phone;
    $_SESSION['old_nationality'] = $nationality;
    $_SESSION['old_id_number'] = $id_number;
    
    $conn->close();
    header("Location: ../views/guest/register.php");
    exit();
}

$conn->close();
?>