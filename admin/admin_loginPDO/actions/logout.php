<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy session and redirect to login page
session_destroy();
header("Location: ../views/login.php");
exit();
?>