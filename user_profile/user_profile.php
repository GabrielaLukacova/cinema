<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session only if it hasn't started yet.
}
require_once '../loginPDO/session.php'; 
require_once 'classes/user.php';
include '../navbar_footer/cinema_navbar.php';
include 'templates/user_sidebar.php';


// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Retrieve the user ID from the session
$userID = $_SESSION['user_id'];

$user = new User();
$userData = $user->getUserProfile($userID);

if (!$userData) {
    die('<p>Error: Unable to load user profile.</p>');
}

include '../navbar_footer/cinema_footer.php';

?>
<link rel="stylesheet" href="../css/style.css">