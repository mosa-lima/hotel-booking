<!DOCTYPE html>
<html>
<head>
    <title>Guest Registration</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>



<?php
session_start();

// If already logged in, redirect
if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'guest'){
    header("Location: dashboard.php");
    exit();
}
?>

<h1>Registration Form</h1>

<?php
// Display error from session if any
if(isset($_SESSION['register_error'])){
    echo "<p class='error'>" . $_SESSION['register_error'] . "</p>";
    unset($_SESSION['register_error']);
}

// Display success from session if any
if(isset($_SESSION['register_success'])){
    echo "<p class='success'>" . $_SESSION['register_success'] . "</p>";
    unset($_SESSION['register_success']);
}
?>

<form action="../../controllers/RegisterCheck.php" method="post">

<table>

<tr>
<td> Name: </td>
<td> <input type="text" name="name" value="<?php echo isset($_SESSION['old_name']) ? $_SESSION['old_name'] : ''; ?>"> </td>
</tr>

<tr>
<td> Email: </td>
<td> <input type="email" name="email" value="<?php echo isset($_SESSION['old_email']) ? $_SESSION['old_email'] : ''; ?>"> </td>
</tr>

<tr>
<td> Password: </td>
<td> <input type="password" name="password"> </td>
</tr>

<tr>
<td> Confirm Password: </td>
<td> <input type="password" name="confirm_password"> </td>
</tr>

<tr>
<td> Phone (11 digits): </td>
<td> <input type="text" name="phone" maxlength="11" value="<?php echo isset($_SESSION['old_phone']) ? $_SESSION['old_phone'] : ''; ?>"> </td>
</tr>

<tr>
<td> Nationality: </td>
<td> <input type="text" name="nationality" value="<?php echo isset($_SESSION['old_nationality']) ? $_SESSION['old_nationality'] : ''; ?>"> </td>
</tr>

<tr>
<td> ID Number: </td>
<td> <input type="text" name="id_number" value="<?php echo isset($_SESSION['old_id_number']) ? $_SESSION['old_id_number'] : ''; ?>"> </td>
</tr>

<tr>
<td> <input type="submit" value="Register"> </td>
</tr>

<tr>
<td colspan="2"> Already have an account? <a href="login.php">Login here</a> </td>
</tr>

</table>

</form>

<?php
// Clear old input from session after displaying
unset($_SESSION['old_name']);
unset($_SESSION['old_email']);
unset($_SESSION['old_phone']);
unset($_SESSION['old_nationality']);
unset($_SESSION['old_id_number']);
?>

</body>
</html>