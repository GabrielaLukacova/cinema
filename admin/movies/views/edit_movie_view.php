<?php 
require_once "../../components/views/admin_navbar.php"; 
require_once "../actions/edit_movie.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit movie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../admin_style/admin_style.css">
</head>
<body>
<div class="container my-5">
    <h2>Edit ,ovie</h2>
    <form method="post" action="edit_movie_view.php" enctype="multipart/form-data" class="p-4 shadow rounded bg-white">
        <input type="hidden" name="movieID" value="<?= htmlspecialchars($movie['movieID'], ENT_QUOTES, 'UTF-8'); ?>">

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8'); ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="genre">Genre</label>
            <input type="text" id="genre" name="genre" value="<?= htmlspecialchars($movie['genre'], ENT_QUOTES, 'UTF-8'); ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="runtime">Runtime (minutes)</label>
            <input type="number" id="runtime" name="runtime" value="<?= htmlspecialchars($movie['runtime'], ENT_QUOTES, 'UTF-8'); ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="language">Language</label>
            <select id="language" name="language" class="form-control" required>
                <option value="English" <?= $movie['language'] === "English" ? "selected" : ""; ?>>English</option>
                <option value="Danish" <?= $movie['language'] === "Danish" ? "selected" : ""; ?>>Danish</option>
            </select>
        </div>
        <div class="form-group">
            <label for="ageRating">Age rating</label>
            <input type="text" id="ageRating" name="ageRating" value="<?= htmlspecialchars($movie['ageRating'], ENT_QUOTES, 'UTF-8'); ?>" class="form-control">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3" required><?= htmlspecialchars($movie['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>
        <div class="form-group">
            <label for="movieImage">Movie Image</label>
            <input type="file" name="movieImage" id="movieImage" class="form-control-file" accept="image/*">
            <p>Current Image:</p>
            <img src="../../../includes/media/movies/<?= htmlspecialchars($movie['imagePath'], ENT_QUOTES, 'UTF-8'); ?>" alt="Current Movie Image" class="img-fluid" style="max-width: 100px;">
        </div>
        <div class="form-group">
            <label for="movieTag">Tag</label>
            <select id="movieTag" name="movieTag" class="form-control">
                <option value="None" <?= $movie['movieTag'] === "None" ? "selected" : ""; ?>>None</option>
                <option value="Hot New Movie" <?= $movie['movieTag'] === "Hot New Movie" ? "selected" : ""; ?>>Hot new movie</option>
                <option value="Movie of the Week" <?= $movie['movieTag'] === "Movie of the Week" ? "selected" : ""; ?>>Movie of the week</option>
            </select>
        </div>
        <button type="submit" name="submit" class="btn btn-primary btn-block">Update movie</button>
    </form>
    <a href="../views/movie_list.php" class="btn btn-secondary mt-3">Back to movie List</a>
</div>
</body>
</html>
