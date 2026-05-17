<?php
session_start();
session_destroy();
header("Location: ../views/guest/login.php");
exit();
?>