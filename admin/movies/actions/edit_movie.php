<?php
require_once "../../../includes/connection.php";
require_once "../classes/Movie.php";

// Initialize the Movie handler
$movieHandler = new Movie($db);

// Handle POST request for updating a movie
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieID = $_POST['movieID'] ?? null;
    $title = $_POST['title'] ?? null;
    $genre = $_POST['genre'] ?? null;
    $runtime = $_POST['runtime'] ?? null;
    $language = $_POST['language'] ?? null;
    $ageRating = $_POST['ageRating'] ?? null;
    $description = $_POST['description'] ?? null;
    $movieTag = $_POST['movieTag'] ?? 'None';
    $imagePath = null;

    // Validate required fields
    if (!$movieID || !$title || !$genre || !$runtime || !$language || !$description) {
        die("Error: Missing required fields for movie update.");
    }

    // Handle image upload
    if (isset($_FILES['movieImage']) && $_FILES['movieImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../../../includes/media/movies/";
        $uploadFile = $uploadDir . basename($_FILES['movieImage']['name']);

        // Move uploaded file to the movies directory
        if (move_uploaded_file($_FILES['movieImage']['tmp_name'], $uploadFile)) {
            $imagePath = basename($_FILES['movieImage']['name']);
        } else {
            die("Error: Failed to upload movie image.");
        }
    }

    try {
        // Fetch the existing movie to retain the current image if no new image is uploaded
        $movie = $movieHandler->getMovieByID($movieID);
        if (!$movie) {
            die("Error: Movie not found.");
        }

        // Use the existing image path if no new image is uploaded
        $imagePath = $imagePath ?? $movie['imagePath'] ?? null;

        // Check if the tag is restricted and if the limit is exceeded
        if ($movieTag === "Movie of the Week") {
            $currentTaggedMovie = $movieHandler->getFirstMovieByTag("Movie of the Week");

            // Redirect to confirm replacement if another movie already has this tag
            if ($currentTaggedMovie && $currentTaggedMovie['movieID'] !== $movieID) {
                header("Location: replace_tag_view.php?firstMovieID={$currentTaggedMovie['movieID']}&movieTag={$movieTag}&newMovieID={$movieID}");
                exit();
            }
        }

        // Update the movie details in the database
        $movieHandler->updateMovie(
            $movieID,
            $title,
            $genre,
            $runtime,
            $language,
            $ageRating,
            $description,
            $movieTag,
            $imagePath // Existing or new image path
        );

        // Redirect to the movie list with a success message
        header("Location: ../views/movie_list.php?status=Movie updated successfully.");
        exit();
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch movie details for editing
if (isset($_GET['movieID'])) {
    $movieID = $_GET['movieID'];
    $movie = $movieHandler->getMovieByID($movieID);

    if (!$movie) {
        die("Error: Movie not found.");
    }
} else {
    header("Location: ../views/movie_list.php");
    exit();
}
?>