<?php
include('../../includes/connection.php'); 
include('classes/Movie.php'); 


// Initialize the Movie object
$movieObj = new Movie($db);

if (isset($_GET['movieID'])) {
    $movieID = intval($_GET['movieID']); // Sanitize input

    // Attempt to delete the movie
    try {
        if ($movieObj->deleteMovie($movieID)) {
            header("Location: movie_list.php?status=deleted&movieID=$movieID");
        } else {
            header("Location: movie_list.php?status=error");
        }
    } catch (Exception $e) {
        // Handle unexpected errors
        header("Location: movie_list.php?status=exception&error=" . urlencode($e->getMessage()));
    }
} else {
    header("Location: movie_list.php?status=invalid");
}
exit;