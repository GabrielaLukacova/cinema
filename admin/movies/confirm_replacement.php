<?php
$firstMovieTitle = htmlspecialchars($firstMovie['title']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Replacement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2>Confirm Replacement</h2>
    <p>The limit for the tag has been reached. Replace movie <strong><?= htmlspecialchars($firstMovieTitle); ?></strong> with this one?</p>
    <form method="post" action="edit_movie.php">
        <!-- Hidden fields to pass data -->
        <input type="hidden" name="replace" value="yes">
        <input type="hidden" name="firstMovieID" value="<?= htmlspecialchars($firstMovie['movieID']); ?>">
        <input type="hidden" name="movieID" value="<?= htmlspecialchars($movie['movieID']); ?>">
        <input type="hidden" name="title" value="<?= htmlspecialchars($movie['title']); ?>">
        <input type="hidden" name="genre" value="<?= htmlspecialchars($movie['genre']); ?>">
        <input type="hidden" name="runtime" value="<?= htmlspecialchars($movie['runtime']); ?>">
        <input type="hidden" name="language" value="<?= htmlspecialchars($movie['language']); ?>">
        <input type="hidden" name="ageRating" value="<?= htmlspecialchars($movie['ageRating']); ?>">
        <input type="hidden" name="description" value="<?= htmlspecialchars($movie['description']); ?>">
        <input type="hidden" name="movieTag" value="<?= htmlspecialchars($movieTag); ?>">

        <button type="submit" class="btn btn-danger">Yes, Replace</button>
        <a href="movie_list.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
