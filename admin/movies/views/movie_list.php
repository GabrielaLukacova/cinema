<?php 
require_once "../../components/views/admin_navbar.php"; 
require_once "../actions/movie_list_logic.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage movies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../../admin_style/admin_style.css">
</head>
<body>
<div class="container my-5">
    <!-- New Movie Button -->
    <div class="d-flex justify-content-end mb-4">
        <a href="#addMovieForm" class="btn btn-success btn-lg">+ New movie</a>
    </div>

    <!-- Page Title -->
    <h2 class="text-center mb-5">🎬 Manage movies</h2>

    <!-- Status Message -->
    <?php if (isset($_GET['status'])): ?>
        <div class="alert alert-info d-flex align-items-center">
            <i class="material-icons me-2">info</i>
            <?= htmlspecialchars($_GET['status']); ?>
        </div>
    <?php endif; ?>

    <!-- Movies Table -->
        <table class="table p-4 shadow rounded bg-white">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Genre</th>
                    <th>Runtime</th>
                    <th>Language</th>
                    <th>Age rating</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Tag</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($movies)): ?>
                    <?php foreach ($movies as $movie): ?>
                        <tr>
                            <td><?= htmlspecialchars($movie['movieID']); ?></td>
                            <td><?= htmlspecialchars($movie['title']); ?></td>
                            <td><?= htmlspecialchars($movie['genre']); ?></td>
                            <td><?= htmlspecialchars($movie['runtime']); ?> min</td>
                            <td><?= htmlspecialchars($movie['language']); ?></td>
                            <td><?= htmlspecialchars($movie['ageRating']); ?></td>
                            <td><?= htmlspecialchars($movie['description']); ?></td>
                            <td>
                                <img src="../../../includes/media/movies/<?= htmlspecialchars($movie['imagePath']); ?>" 
                                     alt="Movie image" 
                                     class="img-thumbnail" 
                                     style="max-width: 100px;">
                            </td>
                            <td><?= htmlspecialchars($movie['movieTag'] ?? 'None'); ?></td>
                            <td>
                                <a href="edit_movie_view.php?movieID=<?= htmlspecialchars($movie['movieID']); ?>" 
                                   class="btn btn-warning btn-sm">Edit</a>
                                <a href="../actions/delete_movie.php?movieID=<?= htmlspecialchars($movie['movieID']); ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this movie?');">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">No movies found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    <!-- Add New Movie Form -->
    <h3 class="mt-5" id="addMovieForm">Add new movie</h3>
    <form method="post" action="../actions/add_movie.php" enctype="multipart/form-data" class="p-4 shadow rounded bg-white">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="genre" class="form-label">Genre</label>
            <input type="text" id="genre" name="genre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="runtime" class="form-label">Runtime (minutes)</label>
            <input type="number" id="runtime" name="runtime" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="language" class="form-label">Language</label>
            <select id="language" name="language" class="form-control" required>
                <option value="English">English</option>
                <option value="Danish">Danish</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="ageRating" class="form-label">Age rating</label>
            <input type="text" id="ageRating" name="ageRating" class="form-control">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="movieImage" class="form-label">Movie image</label>
            <input type="file" name="movieImage" id="movieImage" class="form-control" accept="image/*" required>
        </div>
        <div class="mb-3">
            <label for="movieTag" class="form-label">Tag</label>
            <select id="movieTag" name="movieTag" class="form-control">
                <option value="None">None</option>
                <option value="Hot New Movie" <?= $hotNewCount >= 8 ? 'disabled' : ''; ?>>Hot new movie</option>
                <option value="Movie of the Week" <?= $movieOfWeekCount >= 1 ? 'disabled' : ''; ?>>Movie of the week</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success w-100">+ Add movie</button>
    </form>
</div>
</body>
</html>