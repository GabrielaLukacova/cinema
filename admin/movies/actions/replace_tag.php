<?php
require_once "../../../includes/connection.php";
require_once "../classes/Movie.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/movie_list.php?status=Invalid request method.");
    exit();
}

try {
    $movieHandler = new Movie($db);

    // Validate and sanitize inputs
    $firstMovieID = filter_input(INPUT_POST, 'firstMovieID', FILTER_VALIDATE_INT);
    $newMovieID = filter_input(INPUT_POST, 'newMovieID', FILTER_VALIDATE_INT);
    $movieTag = filter_input(INPUT_POST, 'movieTag', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$firstMovieID || !$newMovieID || !$movieTag) {
        throw new Exception("Invalid input. Missing required parameters.");
    }

    // Fetch and validate the current and new movies
    $firstMovie = $movieHandler->getMovieByID($firstMovieID);
    if (!$firstMovie) {
        throw new Exception("Movie with ID $firstMovieID not found.");
    }

    $newMovie = $movieHandler->getMovieByID($newMovieID);
    if (!$newMovie) {
        throw new Exception("Movie with ID $newMovieID not found.");
    }

    // Update the tag of the first movie to "None"
    if (!$movieHandler->updateMovie(
        $firstMovieID,
        $firstMovie['title'],
        $firstMovie['genre'],
        $firstMovie['runtime'],
        $firstMovie['language'],
        $firstMovie['ageRating'],
        $firstMovie['description'],
        "None",
        $firstMovie['imagePath']
    )) {
        throw new Exception("Failed to remove the tag from the current movie.");
    }

    // Assign the new tag to the new movie
    if (!$movieHandler->updateMovie(
        $newMovieID,
        $newMovie['title'],
        $newMovie['genre'],
        $newMovie['runtime'],
        $newMovie['language'],
        $newMovie['ageRating'],
        $newMovie['description'],
        $movieTag,
        $newMovie['imagePath']
    )) {
        throw new Exception("Failed to assign the new tag to the selected movie.");
    }

    // Redirect with a success message
    header("Location: ../views/movie_list.php?status=Movie tag replaced successfully.");
    exit();
} catch (Exception $e) {
    // Log the error and redirect with a failure message
    error_log("Error in replace_tag.php: " . $e->getMessage());
    header("Location: ../views/movie_list.php?status=Error replacing movie tag: " . urlencode($e->getMessage()));
    exit();
}
?>
