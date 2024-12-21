<?php
require_once "../../../includes/connection.php";
require_once "../classes/Movie.php";

try {
    // Initialize Movie handler
    $movieHandler = new Movie($db);

    // Fetch all movies
    $movies = $movieHandler->getAllMovies();

    // Count movies by tag
    $hotNewCount = $movieHandler->countMoviesByTag('Hot New Movie');
    $movieOfWeekCount = $movieHandler->countMoviesByTag('Movie of the Week');
} catch (Exception $e) {
    error_log("Error fetching movies: " . $e->getMessage());
    die("<div class='alert alert-danger'>An error occurred while fetching movies. Please try again later.</div>");
}
