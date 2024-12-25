<?php
require_once "../../../includes/connection.php";
require_once "../classes/Movie.php";
// Initialize the Movie object
$movieObj = new Movie($db);

if (isset($_GET['movieID'])) {
    $movieID = intval($_GET['movieID']); // Sanitize input

    // Attempt to delete the movie
    try {
        if ($movieObj->deleteMovie($movieID)) {
            header("Location: ../views/movie_list.php?status=deleted&movieID=$movieID");
        } else {
            header("Location: ../views/movie_list.php?status=error");
        }
    } catch (Exception $e) {
        // Handle unexpected errors
        header("Location: ../views/movie_list.php?status=exception&error=" . urlencode($e->getMessage()));
    }
} else {
    header("Location: ../views/movie_list.php?status=invalid");
}
exit;
?>