<?php
require_once "../../../includes/connection.php";
require_once "../classes/Movie.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/movie_list.php?status=Invalid request method.");
    exit();
}

try {
    $movieHandler = new Movie($db);

    // Validate inputs
    $firstMovieID = filter_input(INPUT_POST, 'firstMovieID', FILTER_VALIDATE_INT);
    $newMovieID = filter_input(INPUT_POST, 'newMovieID', FILTER_VALIDATE_INT);
    $movieTag = filter_input(INPUT_POST, 'movieTag', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$firstMovieID || !$newMovieID || !$movieTag) {
        throw new Exception("Invalid input. Missing required parameters.");
    }

    // Fetch the movies
    $firstMovie = $movieHandler->getMovieByID($firstMovieID);
    $newMovie = $movieHandler->getMovieByID($newMovieID);

    if (!$firstMovie || !$newMovie) {
        throw new Exception("One or both movies not found.");
    }

    $newMovie = $movieHandler->getMovieByID($newMovieID);
    if (!$newMovie) {
        throw new Exception("Movie with ID $newMovieID not found.");
    }

   // Remove the tag from the current movie
   $movieHandler->updateMovieWithImage(
    $firstMovieID,
    $firstMovie['title'],
    $firstMovie['genre'],
    $firstMovie['runtime'],
    $firstMovie['language'],
    $firstMovie['ageRating'],
    $firstMovie['description'],
    $firstMovie['imagePath'],
    "None"
);

// Assign the tag to the new movie
$movieHandler->updateMovieWithImage(
    $newMovieID,
    $newMovie['title'],
    $newMovie['genre'],
    $newMovie['runtime'],
    $newMovie['language'],
    $newMovie['ageRating'],
    $newMovie['description'],
    $newMovie['imagePath'],
    $movieTag
);

// Redirect with success
header("Location: ../views/movie_list.php?status=Movie tag replaced successfully.");
exit();
} catch (Exception $e) {
error_log("Error in replace_tag.php: " . $e->getMessage());
header("Location: ../views/movie_list.php?status=Error replacing tag: " . urlencode($e->getMessage()));
exit();
}
?>
