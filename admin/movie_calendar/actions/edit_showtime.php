<?php
require_once "../../../includes/connection.php";

try {
    // Validate and retrieve `showTimeID`
    $showTimeID = $_GET['showTimeID'] ?? null;
    if (!$showTimeID) {
        throw new Exception("Showtime ID not provided.");
    }

    // Fetch showtime details
    $query = $db->prepare("SELECT * FROM ShowTime WHERE showTimeID = :showTimeID");
    $query->execute([':showTimeID' => $showTimeID]);
    $showTime = $query->fetch(PDO::FETCH_ASSOC);

    if (!$showTime) {
        throw new Exception("Showtime not found.");
    }

    // Fetch movies for dropdown
    $moviesQuery = $db->query("SELECT movieID, title FROM Movie");
    $movies = $moviesQuery->fetchAll(PDO::FETCH_ASSOC);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $movieID = $_POST['movieID'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $room = $_POST['room'];

        // Update showtime in the database
        $updateQuery = $db->prepare("
            UPDATE ShowTime 
            SET movieID = :movieID, date = :date, time = :time, room = :room 
            WHERE showTimeID = :showTimeID
        ");
        $executeResult = $updateQuery->execute([
            ':movieID' => $movieID,
            ':date' => $date,
            ':time' => $time,
            ':room' => $room,
            ':showTimeID' => $showTimeID
        ]);

        if ($executeResult) {
            header("Location: ../views/movie_calendar_view.php?status=updated");
            exit;
        } else {
            throw new Exception("Error updating showtime: " . implode(", ", $updateQuery->errorInfo()));
        }
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    $errorMessage = $e->getMessage();
}
?>
