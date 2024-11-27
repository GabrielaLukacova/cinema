<?php 
require_once("../../includes/connection.php"); 
include '../components/admin_navbar.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Managing Movies</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../admin_style/admin_style.css?v=1.2">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php
// Fetch movies from the database
$query = $db->prepare("SELECT * FROM Movie");
$query->execute();
$getMovies = $query->fetchAll();
?>


<div class="container my-7">
    
<!-- Button to Scroll to Form -->
<div class="container my-4 text-end">
    <a href="#addMovieForm" class="btn btn-success btn-lg">+ New movie</a>
</div>

    <h2 class="text-center mb-5">🎬 Manage movies</h2>

    <?php if (isset($_GET['status'])): ?>
        <div class="alert alert-info">
            <i class="material-icons btn-icon">info</i>
            <?php echo htmlspecialchars($_GET['status']); ?>
        </div>
    <?php endif; ?>
    
    <div class="table-responsive shadow-lg rounded sw-100">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Movie ID</th>
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
                <?php foreach ($getMovies as $movie): ?>
                    <tr class="movie-card">
                        <td><?php echo htmlspecialchars($movie['movieID']?? ''); ?></td>
                        <td><?php echo htmlspecialchars($movie['title']?? ''); ?></td>
                        <td><?php echo htmlspecialchars($movie['genre']?? ''); ?></td>
                        <td><?php echo htmlspecialchars($movie['runtime']?? ''); ?> min</td>
                        <td><?php echo htmlspecialchars($movie['language']?? ''); ?></td>
                        <td><?php echo htmlspecialchars($movie['ageRating']?? ''); ?></td>
                        <td><?php echo htmlspecialchars($movie['description']?? ''); ?></td>
                        <td>
                            <img src="../../includes/media/movies/<?php echo htmlspecialchars($movie['imagePath']?? ''); ?>" alt="Movie Image" class="movie-image">
                        </td>
                        <td><?php echo htmlspecialchars($movie['movieTag']?? ''); ?></td>
                        <td>
                            <a href="editMovie.php?movieID=<?php echo $movie['movieID']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="deleteMovie.php?movieID=<?php echo $movie['movieID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this movie?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h3 class="mt-5">Add new movie</h3>
    <form id="addMovieForm" method="post" action="addMovie.php" enctype="multipart/form-data" class="p-4 shadow rounded bg-white">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="genre">Genre</label>
            <input type="text" id="genre" name="genre" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="runtime">Runtime (minutes)</label>
            <input type="number" id="runtime" name="runtime" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="language">Language</label>
            <select id="language" name="language" class="form-control" required>
                <option value="English">English</option>
                <option value="Danish">Danish</option>
            </select>
        </div>
        <div class="form-group">
            <label for="ageRating">Age rating</label>
            <input type="text" id="ageRating" name="ageRating" class="form-control">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="movieImage">Movie image</label>
            <input type="file" name="movieImage" id="movieImage" class="form-control-file" accept="image/*">
        </div>
        <div class="form-group">
            <label for="movieTag">Tag</label>
            <select id="movieTag" name="movieTag" class="form-control" required>
                <option value="None">None</option>
                <option value="Hot New Movie">Hot new movie</option>
                <option value="Movie of the Week">Movie of the week</option>
            </select>
        </div>
        <button type="submit" name="submit" class="btn btn-success btn-block">
        + Add Movie
        </button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


