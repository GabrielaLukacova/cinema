<?php
include '../../includes/connection.php';
include 'classes/Movie.php';

$movie = new Movie($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Handle file upload
        if (isset($_FILES['movieImage']) && $_FILES['movieImage']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../includes/media/movies/';
            $fileName = basename($_FILES['movieImage']['name']);
            $targetFilePath = $uploadDir . $fileName;

            // Validate file type
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($fileType), $allowedTypes)) {
                throw new Exception("Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.");
            }

            // Move the uploaded file to the target directory
            if (!move_uploaded_file($_FILES['movieImage']['tmp_name'], $targetFilePath)) {
                throw new Exception("Failed to upload the image.");
            }

            // Store the relative path in the database
            $imagePath = $fileName;
        } else {
            $imagePath = null; // Or set a default image path
        }

        // Prepare data for the movie
        $data = [
            'title' => $_POST['title'],
            'genre' => $_POST['genre'],
            'runtime' => $_POST['runtime'],
            'language' => $_POST['language'],
            'languageFlagPath' => $_POST['languageFlagPath'] ?? null,
            'ageRating' => $_POST['ageRating'] ?? null,
            'description' => $_POST['description'] ?? null,
            'imagePath' => $imagePath,
            'movieTag' => $_POST['movieTag'] ?? null,
        ];

        // Add movie to the database
        if ($movie->addMovie($data)) {
            header("Location: movie_list.php?success=Movie added successfully!");
            exit();
        } else {
            $error = "Failed to add movie.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
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
    <form method="POST" action="" enctype="multipart/form-data">
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
            <select id="language" name="language" class="form-control" required>
                <option value="English">English</option>
                <option value="Danish">Danish</option>
            </select>
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
            <label for="movieImage" class="form-label">Movie Image</label>
            <input type="file" name="movieImage" id="movieImage" class="form-control-file" accept="image/*" required>
        </div>
        <div class="mb-3">
            <label for="movieTag" class="form-label">Movie Tag</label>
            <select id="movieTag" name="movieTag" class="form-control">
                <option value="None">None</option>
                <option value="Hot New Movie">Hot New Movie</option>
                <option value="Movie of the Week">Movie of the Week</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Movie</button>
    </form>
</div>
