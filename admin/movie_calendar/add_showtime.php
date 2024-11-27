<?php
require_once("../../includes/connection.php");
include '../includes/admin_navbar.php'; 


// Handle form submission for adding new showtime
if (isset($_POST['submit'])) {
    $movieID = $_POST['movieID'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $room = $_POST['room'];
    $price = $_POST['price'];

    // Insert showtime data into the database
    $insertQuery = $db->prepare("INSERT INTO ShowTime (movieID, date, time, room, price) 
                                   VALUES (:movieID, :date, :time, :room, :price)");
    $executeResult = $insertQuery->execute([
        ':movieID' => $movieID,
        ':date' => $date,
        ':time' => $time,
        ':room' => $room,
        ':price' => $price
    ]);

    if ($executeResult) {
        // Redirect back to the movie_calendar.php page with success status
        header("Location: movie_calendar.php?status=added");
        exit;
    } else {
        // Redirect back with error status
        $errorMessage = "Error adding showtime: " . implode(", ", $insertQuery->errorInfo());
        header("Location: movie_calendar.php?status=error&message=" . urlencode($errorMessage));
        exit;
    }
}
?>

