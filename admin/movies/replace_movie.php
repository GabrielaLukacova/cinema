<?php
require_once "../../includes/connection.php";
require_once "classes/Movie.php";

$movieHandler = new Movie($db);

$movieID = $_GET['movieID'];
$newTag = $_GET['newTag'];

try {
    // Fetch existing movie details
    $currentMovie = $movieHandler->getMovieByID($movieID);
    if (!$currentMovie) {
        throw new Exception("Movie with ID $movieID not found.");
    }

    // Remove the tag from the current movie
    $movieHandler->updateMovie(
        $movieID, 
        $currentMovie['title'], 
        $currentMovie['genre'], 
        $currentMovie['runtime'], 
        $currentMovie['language'], 
        $currentMovie['ageRating'], 
        $currentMovie['description'], 
        "None"
    );

    // Update the new movie with the tag
    $newMovieID = $_POST['newMovieID']; // Ensure this value is passed from the form
    $newMovie = $movieHandler->getMovieByID($newMovieID);
    if (!$newMovie) {
        throw new Exception("New movie with ID $newMovieID not found.");
    }

    $movieHandler->updateMovie(
        $newMovieID, 
        $newMovie['title'], 
        $newMovie['genre'], 
        $newMovie['runtime'], 
        $newMovie['language'], 
        $newMovie['ageRating'], 
        $newMovie['description'], 
        $newTag
    );

    header("Location: manage_movies.php?status=Movie tag replaced successfully.");
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?> confirm_replacement.php: <?php
// Variables passed from edit_movie.php
$firstMovieTitle = htmlspecialchars($firstMovie['title']);
$firstMovieID = htmlspecialchars($firstMovie['movieID']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Replacement</title>
    <link rel="stylesheet" href="../admin_style/admin_style.css?v=1.2"></head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2 class="mb-4">Confirm Replacement</h2>
    <div class="alert alert-warning">
        <p>The limit for the tag <strong><?= htmlspecialchars($movieTag); ?></strong> has been reached.</p>
        <p>Do you want to replace the movie <strong><?= htmlspecialchars($firstMovie['title']); ?></strong> with this one?</p>
    </div>
    <form method="post" action="edit_movie.php" class="p-4 shadow rounded bg-white">
        <!-- Hidden fields to pass data -->
        <input type="hidden" name="replace" value="yes">
        <input type="hidden" name="firstMovieID" value="<?= htmlspecialchars($firstMovie['movieID']); ?>">
        <input type="hidden" name="movieID" value="<?= htmlspecialchars($movieID); ?>">
        <input type="hidden" name="title" value="<?= htmlspecialchars($title); ?>">
        <input type="hidden" name="genre" value="<?= htmlspecialchars($genre); ?>">
        <input type="hidden" name="runtime" value="<?= htmlspecialchars($runtime); ?>">
        <input type="hidden" name="language" value="<?= htmlspecialchars($language); ?>">
        <input type="hidden" name="ageRating" value="<?= htmlspecialchars($ageRating); ?>">
        <input type="hidden" name="description" value="<?= htmlspecialchars($description); ?>">
        <input type="hidden" name="movieTag" value="<?= htmlspecialchars($movieTag); ?>">

        <!-- Replace Button -->
        <button type="submit" class="btn btn-danger">Yes, Replace</button>

        <!-- Cancel Button -->
        <a href="movie_list.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>