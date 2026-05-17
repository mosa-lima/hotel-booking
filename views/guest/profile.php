<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>



<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guest'){
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hotel_booking_system";
$conn = new mysqli($host, $user, $pass, $dbname);

$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email, phone, nationality, id_number, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$stmt->close();
$conn->close();

$success = $_SESSION['profile_success'] ?? '';
$error = $_SESSION['profile_error'] ?? '';
unset($_SESSION['profile_success']);
unset($_SESSION['profile_error']);
?>

<div class="menu">
    <a href="dashboard.php">Dashboard</a>
    <a href="search_room.php">Search Rooms</a>
    <a href="my_bookings.php">My Bookings</a>
    <a href="profile.php">My Profile</a>
    <a href="service_request.php">Service Requests</a>
    <a href="billing_history.php">Billing</a>
    <a href="../../controllers/Logout.php">Logout</a>
</div>

<h1>My Profile</h1>

<?php if($success): ?>
    <p class="success"><?php echo $success; ?></p>
<?php endif; ?>
<?php if($error): ?>
    <p class="error"><?php echo $error; ?></p>
<?php endif; ?>

<form action="../../controllers/UpdateProfile.php" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td>Name: </td>
            <td><input type="text" name="name" value="<?php echo $user_data['name']; ?>" required> </td>
        </tr>
        <tr>
            <td>Email: </td>
            <td><input type="email" value="<?php echo $user_data['email']; ?>" disabled> </td>
        </tr>
        <tr>
            <td>Phone: </td>
            <td><input type="text" name="phone" value="<?php echo $user_data['phone']; ?>" required> </td>
        </tr>
        <tr>
            <td>Nationality: </td>
            <td><input type="text" name="nationality" value="<?php echo $user_data['nationality']; ?>"> </td>
        </tr>
        <tr>
            <td>ID Number: </td>
            <td><input type="text" name="id_number" value="<?php echo $user_data['id_number']; ?>"> </td>
        </tr>
        <tr>
            <td>Profile Picture: </td>
            <td><input type="file" name="profile_pic"> </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Update Profile">
            </td>
        </tr>
    </table>
</form>

<h2>Change Password</h2>
<form action="../../controllers/ChangePassword.php" method="post">
    <table>
        <tr>
            <td>Old Password: </td>
            <td><input type="password" name="old_password" required> </td>
        </tr>
        <tr>
            <td>New Password: </td>
            <td><input type="password" name="new_password" required> </td>
        </tr>
        <tr>
            <td>Confirm New Password: </td>
            <td><input type="password" name="confirm_password" required> </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Change Password">
            </td>
        </tr>
    </table>
</form>

</body>
</html>