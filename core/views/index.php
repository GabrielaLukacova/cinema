<?php
require_once "../../includes/connection.php";
require_once "../../navbar_footer/cinema_navbar.php";
// require_once("loginPDO/session.php");



header("Location: home.php");
exit();






// Check if the user is logged in
if (!logged_in()) {
    // Redirect to the login page if not logged in
    header("Location: loginPDO/login.php");
    exit;
}



session_start();
if (!isset($_SESSION['user_id'])) {
    // if user is not logged in, show login form
    include("login.php"); 
} else {
    echo '<p>Welcome, ' . htmlspecialchars($_SESSION['email']) . '</p>';
}


//Dispaly only hours and minutes (no seconds)
try {
    // Fetch a showtime time and format
    $showTimeQuery = $db->prepare("
        SELECT DATE_FORMAT(time, '%H:%i') AS formattedTime 
        FROM ShowTime
    ");
    $showTimeQuery->execute();
    $showTimes = $showTimeQuery->fetchAll(PDO::FETCH_ASSOC);

    // Example output 
    foreach ($showTimes as $showTime) {
        // Log or echo the raw time data to check
        echo "<p>Raw Time: " . htmlspecialchars($showTime['formattedTime'], ENT_QUOTES, 'UTF-8') . "</p>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dream Screen - Cinema</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>