<?php
include '../../includes/connection.php';
include 'classes/Movie.php';

$movie = new Movie($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'],
        'genre' => $_POST['genre'],
        'runtime' => $_POST['runtime'],
        'language' => $_POST['language'],
        'languageFlagPath' => $_POST['languageFlagPath'],
        'ageRating' => $_POST['ageRating'],
        'description' => $_POST['description'],
        'imagePath' => $_POST['imagePath'],
        'movieTag' => $_POST['movieTag']
    ];

    if ($movie->addMovie($data)) {
        header("Location: movie_list.php?success=Movie added successfully!");
        exit();
    } else {
        $error = "Failed to add movie.";
    }
}
?>

<div class="container mt-5">
    <h1 class="mb-4">Add New Movie</h1>
    <?php if (isset($error)) : ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="genre" class="form-label">Genre</label>
            <input type="text" class="form-control" id="genre" name="genre" required>
        </div>
        <div class="mb-3">
            <label for="runtime" class="form-label">Runtime (min)</label>
            <input type="number" class="form-control" id="runtime" name="runtime" required>
        </div>
        <div class="mb-3">
            <label for="language" class="form-label">Language</label>
            <input type="text" class="form-control" id="language" name="language" required>
        </div>
        <div class="mb-3">
            <label for="languageFlagPath" class="form-label">Language Flag Path</label>
            <input type="text" class="form-control" id="languageFlagPath" name="languageFlagPath">
        </div>
        <div class="mb-3">
            <label for="ageRating" class="form-label">Age Rating</label>
            <input type="text" class="form-control" id="ageRating" name="ageRating">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="mb-3">
            <label for="imagePath" class="form-label">Image Path</label>
            <input type="text" class="form-control" id="imagePath" name="imagePath">
        </div>
        <div class="mb-3">
            <label for="movieTag" class="form-label">Movie Tag</label>
            <input type="text" class="form-control" id="movieTag" name="movieTag">
        </div>
        <button type="submit" class="btn btn-primary">Add Movie</button>
    </form>
</div>


