<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../includes/connection.php";

try {
    // Fetch the movie tagged as "Movie of the Week"
    $query = $db->prepare("
        SELECT title, imagePath, genre, description, movieID
        FROM Movie
        WHERE movieTag = 'Movie of the Week'
        LIMIT 1
    ");
    $query->execute();
    $movieOfTheWeek = $query->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Log and handle errors gracefully
    error_log("Error fetching Movie of the Week: " . $e->getMessage());
    $movieOfTheWeek = null;
}

// Handle defaults if no movie is found
if (!$movieOfTheWeek) {
    $movieOfTheWeek = [
        'title' => 'Default Movie',
        'imagePath' => 'default.jpg',
        'genre' => 'Unknown Genre',
        'description' => 'No description available.',
        'movieID' => 0
    ];
}

// Prepare a short description for display
$shortDescription = implode(' ', array_slice(explode(' ', $movieOfTheWeek['description']), 0, 20)) . '...';
?>
