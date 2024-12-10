<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once "../../includes/connection.php";
require_once "../../navbar_footer/cinema_navbar.php";
require_once "../../admin/movies/classes/movie.php";
require_once "../actions/showtime_selection.php"; 

if (isset($_GET['showTimeID'])) {
    require_once "../actions/seat_selection.php"; // Fetches `$seats`
}

// Validate and retrieve `movieID`
$movieID = isset($_GET['movieID']) ? (int)$_GET['movieID'] : 0;
if ($movieID <= 0) die("<p>Error: Movie ID not specified or invalid.</p>");

$movieHandler = new Movie($db);
$movie = $movieHandler->getMovieByID($movieID);
if (!$movie) die("<p>Error: Movie not found.</p>");

// Fetch showtimes
require_once "../actions/showtime_selection.php";
$showtimes = $showtimes ?? [];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8'); ?> - Movie Details</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <!-- Hero Section -->
    <div class="movie_single_hero" style="background-image: url('../../includes/media/movies/<?= htmlspecialchars($movie['imagePath'], ENT_QUOTES, 'UTF-8'); ?>');">
        <div class="movie_single_overlay">
            <h3><?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
        </div>
    </div>

    <!-- Movie Info Section -->
    <div class="movie_single_info_container">
        <p>Genre: <?= htmlspecialchars($movie['genre'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Runtime: <?= htmlspecialchars($movie['runtime'], ENT_QUOTES, 'UTF-8'); ?> minutes</p>
        <p>Age Rating: <?= htmlspecialchars($movie['ageRating'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><?= htmlspecialchars($movie['description'], ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

    <!-- Showtimes Section -->
    <div class="movie-calendar-single">
    <h2>Pick a Date and Time</h2>
    <?php if (!empty($showtimes)): ?>
        <?php foreach ($showtimes as $showtime): ?>
            <a href="seat_map.php?movieID=<?= htmlspecialchars($movieID, ENT_QUOTES, 'UTF-8'); ?>&showTimeID=<?= htmlspecialchars($showtime['showTimeID'], ENT_QUOTES, 'UTF-8'); ?>">
    <button class="btn-primary"><?= htmlspecialchars($showtime['time'], ENT_QUOTES, 'UTF-8'); ?></button>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No showtimes available for this movie.</p>
    <?php endif; ?>
</div>






