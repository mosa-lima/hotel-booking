<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'guest') {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Guest Login</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

<h1>Login</h1>

<?php
if (isset($_SESSION['login_error'])) {
    echo '<p class="error">' . htmlspecialchars($_SESSION['login_error']) . '</p>';
    unset($_SESSION['login_error']);
}
if (isset($_SESSION['register_success'])) {
    echo '<p class="success">' . htmlspecialchars($_SESSION['register_success']) . '</p>';
    unset($_SESSION['register_success']);
}
?>

<form action="../../controllers/LoginCheck.php" method="post">
    <table>
        <tr>
            <td>Email: </td>
            <td><input type="email" name="email" required> </td>
        </tr>
        <tr>
            <td>Password: </td>
            <td><input type="password" name="password" required> </td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Login"></td>
        </tr>
        <tr>
            <td colspan="2">Don't have an account? <a href="register.php">Register here</a></td>
        </tr>
    </table>
</form>

</body>
</html>