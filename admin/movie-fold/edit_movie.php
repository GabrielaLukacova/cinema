<?php
include '../../includes/connection.php';
include 'classes/Movie.php';

$movie = new Movie($db);

if (!isset($_GET['id'])) {
    header("Location: movie_list.php");
    exit();
}

$id = $_GET['id'];
$currentMovie = $movie->getMovieById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'movieID' => $id,
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

    if ($movie->updateMovie($data)) {
        header("Location: movie_list.php?success=Movie updated successfully!");
        exit();
    } else {
        $error = "Failed to update movie.";
    }
}
?>

<div class="container mt-5">
    <h1 class="mb-4">Edit Movie</h1>
    <?php if (isset($error)) : ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($currentMovie['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="genre" class="form-label">Genre</label>
            <input type="text" class="form-control" id="genre" name="genre" value="<?= htmlspecialchars($currentMovie['genre']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="runtime" class="form-label">Runtime (min)</label>
            <input type="number" class="form-control" id="runtime" name="runtime" value="<?= htmlspecialchars($currentMovie['runtime']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="language" class="form-label">Language</label>
            <input type="text" class="form-control" id="language" name="language" value="<?= htmlspecialchars($currentMovie['language']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="languageFlagPath" class="form-label">Language Flag Path</label>
            <input type="text" class="form-control" id="languageFlagPath" name="languageFlagPath" value="<?= htmlspecialchars($currentMovie['languageFlagPath']); ?>">
        </div>
        <div class="mb-3">
            <label for="ageRating" class="form-label">Age Rating</label>
            <input type="text" class="form-control" id="ageRating" name="ageRating" value="<?= htmlspecialchars($currentMovie['ageRating']); ?>">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"><?= htmlspecialchars($currentMovie['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="imagePath" class="form-label">Image Path</label>
            <input type="text" class="form-control" id="imagePath" name="imagePath" value="<?= htmlspecialchars($currentMovie['imagePath']); ?>">
        </div>
        <div class="mb-3">
            <label for="movieTag" class="form-label">Movie Tag</label>
            <input type="text" class="form-control" id="movieTag" name="movieTag" value="<?= htmlspecialchars($currentMovie['movieTag']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Movie</button>
    </form>
</div>
