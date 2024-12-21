<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../components/views/admin_navbar.php";
require_once "../classes/Movie.php";
require_once "../actions/replace_tag.php";
require_once "../../../includes/connection.php";

// Initialize the Movie handler
$movieHandler = new Movie($db);

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception("Invalid request method.");
    }

    // Validate and sanitize GET inputs
    $movieID = filter_input(INPUT_GET, 'movieID', FILTER_VALIDATE_INT);
    $movieTag = filter_input(INPUT_GET, 'movieTag', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$movieID || !$movieTag) {
        throw new Exception("Invalid or missing parameters for movie replacement.");
    }

    // Fetch the first movie with the given tag
    $firstMovie = $movieHandler->getFirstMovieByTag($movieTag);
    if (!$firstMovie) {
        throw new Exception("No movie found with the tag '$movieTag'.");
    }

    // Fetch the selected movie to validate
    $newMovie = $movieHandler->getMovieByID($movieID);
    if (!$newMovie) {
        throw new Exception("Selected movie not found.");
    }

    // Prepare data for rendering
    $firstMovieTitle = htmlspecialchars($firstMovie['title'], ENT_QUOTES, 'UTF-8');
    $firstMovieID = htmlspecialchars($firstMovie['movieID'], ENT_QUOTES, 'UTF-8');
    $movieTitle = htmlspecialchars($newMovie['title'], ENT_QUOTES, 'UTF-8');
    $movieID = htmlspecialchars($newMovie['movieID'], ENT_QUOTES, 'UTF-8');
    $movieTag = htmlspecialchars($movieTag, ENT_QUOTES, 'UTF-8');
} catch (Exception $e) {
    // Handle errors gracefully
    error_log("Error in replace_tag_view.php: " . $e->getMessage());
    die("Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Tag Replacement</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2 class="mb-4">Confirm Tag Replacement</h2>
    <div class="alert alert-warning">
        <p>The limit for the tag <strong><?= $movieTag; ?></strong> has been reached.</p>
        <p>Do you want to replace the movie <strong><?= $firstMovieTitle; ?></strong> with <strong><?= $movieTitle; ?></strong>?</p>
    </div>
    <form method="post" action="../actions/replace_tag.php" class="p-4 shadow rounded bg-white">
        <input type="hidden" name="newMovieID" value="<?= $movieID; ?>">
        <input type="hidden" name="movieTag" value="<?= $movieTag; ?>">
        <input type="hidden" name="firstMovieID" value="<?= $firstMovieID; ?>">

        <button type="submit" class="btn btn-danger">Yes, Replace</button>
        <a href="../views/movie_list.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
