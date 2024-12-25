<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../includes/connection.php";
require_once "../../admin/movies/classes/movie.php"; 

try {
    $movieHandler = new Movie($db);

    // Fetch all movies tagged as "Hot New Movie"
    $movies = $movieHandler->getMoviesByTag("Hot New Movie");
} catch (Exception $e) {
    $movies = []; // Fallback to an empty array if fetching fails
    error_log("Error fetching movies: " . $e->getMessage());
}

$moviesPerSection = 5; 
$totalMovies = count($movies);
$totalSections = ceil($totalMovies / $moviesPerSection);

// Get the current section from the query string
$currentSection = isset($_GET['section']) ? (int)$_GET['section'] : 1;
$currentSection = max(1, min($currentSection, $totalSections)); // Clamp value between 1 and $totalSections

// Determine the slice of movies to show
$startIndex = ($currentSection - 1) * $moviesPerSection;
$moviesToShow = array_slice($movies, $startIndex, $moviesPerSection);
?>
