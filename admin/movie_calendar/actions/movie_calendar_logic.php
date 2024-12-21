<?php
require_once "../../../includes/connection.php";

try {
    // Fetch all movies with their showtimes
    $query = $db->prepare("
        SELECT ShowTime.showTimeID, Movie.title, ShowTime.date, ShowTime.time, ShowTime.room, ShowTime.price, Movie.imagePath
        FROM ShowTime
        JOIN Movie ON ShowTime.movieID = Movie.movieID
        ORDER BY ShowTime.date ASC, ShowTime.time ASC
    ");
    $query->execute();
    $movieShowtimes = $query->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all movies for the dropdown
    $queryMovies = $db->prepare("SELECT movieID, title FROM Movie");
    $queryMovies->execute();
    $movies = $queryMovies->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    die("<div class='alert alert-danger'>An error occurred while fetching data. Please try again later.</div>");
}
?>
