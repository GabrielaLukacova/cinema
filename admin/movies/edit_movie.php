<?php
require_once "../../includes/connection.php";
require_once "classes/Movie.php";

$movieHandler = new Movie($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['replace']) && $_POST['replace'] === 'yes') {
        $firstMovieID = $_POST['firstMovieID'] ?? null;
        $movieID = $_POST['movieID'] ?? null;
        $title = $_POST['title'] ?? null;
        $genre = $_POST['genre'] ?? null;
        $runtime = $_POST['runtime'] ?? null;
        $language = $_POST['language'] ?? null;
        $ageRating = $_POST['ageRating'] ?? null;
        $description = $_POST['description'] ?? null;
        $movieTag = $_POST['movieTag'] ?? null;

        // Validate required fields
        if (!$firstMovieID || !$movieID || !$title || !$genre || !$runtime || !$language || !$ageRating || !$description || !$movieTag) {
            die("Error: Missing required data in replacement action.");
        }

        try {
            // Remove the tag from the first movie
            $firstMovie = $movieHandler->getMovieByID($firstMovieID);
            if ($firstMovie) {
                $movieHandler->updateMovie(
                    $firstMovieID,
                    $firstMovie['title'],
                    $firstMovie['genre'],
                    $firstMovie['runtime'],
                    $firstMovie['language'],
                    $firstMovie['ageRating'],
                    $firstMovie['description'],
                    'None'
                );
            }

            // Assign the tag to the selected movie
            $movieHandler->updateMovie(
                $movieID,
                $title,
                $genre,
                $runtime,
                $language,
                $ageRating,
                $description,
                $movieTag
            );

            header("Location: movie_list.php?status=Movie tag replaced successfully.");
            exit;
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

 

    // Handle regular movie update
    if (isset($_POST['movieID'])) {
        $movieID = $_POST['movieID'];
        $title = $_POST['title'] ?? null;
        $genre = $_POST['genre'] ?? null;
        $runtime = $_POST['runtime'] ?? null;
        $language = $_POST['language'] ?? null;
        $ageRating = $_POST['ageRating'] ?? null;
        $description = $_POST['description'] ?? null;
        $movieTag = $_POST['movieTag'] ?? 'None';

        if (!$title || !$genre || !$runtime || !$language || !$description) {
            die("Error: Missing required fields for movie update.");
        }

        try {
            $result = $movieHandler->updateMovie($movieID, $title, $genre, $runtime, $language, $ageRating, $description, $movieTag);
            if ($result) {
                header("Location: movie_list.php?status=Movie updated successfully.");
                exit;
            } else {
                echo "Error: Failed to update the movie.";
            }
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
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
    header("Location: movie_list.php");
    exit;
}
?>





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../admin_style/admin_style.css?v=1.2"></head>
<body>
<div class="container my-5">
    <h2>Edit Movie</h2>
    <form method="post" action="edit_movie.php" enctype="multipart/form-data" class="p-4 shadow rounded bg-white">
        <input type="hidden" name="movieID" value="<?= htmlspecialchars($movie['movieID']); ?>">

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($movie['title']); ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="genre">Genre</label>
            <input type="text" id="genre" name="genre" value="<?= htmlspecialchars($movie['genre']); ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="runtime">Runtime (minutes)</label>
            <input type="number" id="runtime" name="runtime" value="<?= htmlspecialchars($movie['runtime']); ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="language">Language</label>
            <select id="language" name="language" class="form-control" required>
                <option value="English" <?= $movie['language'] == "English" ? "selected" : ""; ?>>English</option>
                <option value="Danish" <?= $movie['language'] == "Danish" ? "selected" : ""; ?>>Danish</option>
            </select>
        </div>
        <div class="form-group">
            <label for="ageRating">Age Rating</label>
            <input type="text" id="ageRating" name="ageRating" value="<?= htmlspecialchars($movie['ageRating']); ?>" class="form-control">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3" required><?= htmlspecialchars($movie['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="movieImage">Movie Image</label>
            <input type="file" name="movieImage" id="movieImage" class="form-control-file" accept="image/*">
            <p>Current Image:</p>
            <img src="../../includes/media/movies/<?= htmlspecialchars($movie['imagePath']); ?>" alt="Current Movie Image" class="img-fluid" style="max-width: 100px;">
        </div>
        <div class="form-group">
            <label for="movieTag">Tag</label>
            <select id="movieTag" name="movieTag" class="form-control">
                <option value="None" <?= $movie['movieTag'] == "None" ? "selected" : ""; ?>>None</option>
                <option value="Hot New Movie" <?= $movie['movieTag'] == "Hot New Movie" ? "selected" : ""; ?>>Hot New Movie</option>
                <option value="Movie of the Week" <?= $movie['movieTag'] == "Movie of the Week" ? "selected" : ""; ?>>Movie of the Week</option>
            </select>
        </div>
        <button type="submit" name="submit" class="btn btn-primary btn-block">Update Movie</button>
    </form>
    <a href="movie_list.php" class="btn btn-secondary mt-3">Back to Movie List</a>
</div>
</body>
</html>