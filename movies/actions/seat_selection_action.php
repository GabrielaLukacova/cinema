<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../includes/connection.php";
require_once "../../admin/movies/classes/movie.php";

// Validate `movieID`
$movieID = isset($_GET['movieID']) && is_numeric($_GET['movieID'])
    ? (int)$_GET['movieID']
    : die("Error: Movie ID not set or invalid.");

// Initialize Movie class and fetch movie details
$movieHandler = new Movie($db);
$movie = $movieHandler->getMovieByID($movieID);
if (!$movie) {
    die("Error: Movie not found.");
}

// Get the selected date from the URL or default to today
$selectedDate = isset($_GET['date']) ? $_GET['date'] : (new DateTime())->format('Y-m-d');

// Fetch available dates for the movie
$dateQuery = $db->prepare("
    SELECT DISTINCT date 
    FROM ShowTime 
    WHERE movieID = :movieID 
    ORDER BY date ASC
");
$dateQuery->execute([':movieID' => $movieID]);
$availableDates = $dateQuery->fetchAll(PDO::FETCH_COLUMN);

// Fetch showtimes for the selected date
$showtimeQuery = $db->prepare("
    SELECT showTimeID, time 
    FROM ShowTime 
    WHERE movieID = :movieID AND date = :date 
    ORDER BY time ASC
");
$showtimeQuery->execute([':movieID' => $movieID, ':date' => $selectedDate]);
$showtimes = $showtimeQuery->fetchAll(PDO::FETCH_ASSOC);
?>