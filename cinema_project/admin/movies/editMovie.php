<?php 
require_once("../../includes/connection.php"); 
include '../includes/admin_navbar.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Managing Movies</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../includes/admin_style.css?v=1.2">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php
$showModal = false;
$movieOfTheWeekCount = 0;
$hotNewMoviesCount = 0;
$replaceMovieTitle = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieID = $_POST['movieID'];
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $runtime = $_POST['runtime'];
    $language = $_POST['language'];
    $ageRating = $_POST['ageRating'];
    $description = $_POST['description'];
    $tagType = $_POST['tagType']; 

    // Check if the selected tag type is "Movie of the Week"
    if ($tagType == "Movie of the Week") {
        $query = $db->prepare("SELECT COUNT(*) FROM Movie WHERE tagType = 'Movie of the Week'");
        $query->execute();
        $movieOfTheWeekCount = $query->fetchColumn();

        if ($movieOfTheWeekCount >= 1) {
            // Get the title of the movie to be replaced
            $query = $db->prepare("SELECT title FROM Movie WHERE tagType = 'Movie of the Week' LIMIT 1");
            $query->execute();
            $replaceMovieTitle = $query->fetchColumn();
            $showModal = 'replaceMovie';
        }
    }

    // Check if the selected tag type is "Hot New Movie"
    if ($tagType == "Hot New Movie") {
        $query = $db->prepare("SELECT COUNT(*) FROM Movie WHERE tagType = 'Hot New Movie'");
        $query->execute();
        $hotNewMoviesCount = $query->fetchColumn();

        if ($hotNewMoviesCount >= 8) {
            // Get the title of the movie to be replaced
            $query = $db->prepare("SELECT title FROM Movie WHERE tagType = 'Hot New Movie' LIMIT 1");
            $query->execute();
            $replaceMovieTitle = $query->fetchColumn();
            $showModal = 'replaceHotNewMovie';
        }
    }

    if (!$showModal) {
        // Proceed with updating the movie
        $query = $db->prepare("SELECT imagePath FROM Movie WHERE movieID = :movieID");
        $query->bindParam(':movieID', $movieID, PDO::PARAM_INT);
        $query->execute();
        $movie = $query->fetch(PDO::FETCH_ASSOC);

        if (!$movie) {
            header("Location: movies.php?status=notfound");
            exit;
        }

        $fileName = $movie['imagePath'];
        if (isset($_FILES['movieImage']) && $_FILES['movieImage']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "../../includes/media/movies/";
            $fileName = basename($_FILES['movieImage']['name']);
            $targetFilePath = $targetDir . $fileName;
            if (!move_uploaded_file($_FILES['movieImage']['tmp_name'], $targetFilePath)) {
                echo "Error uploading image.";
                exit;
            }
        }

        // Update the movie details
        $query = $db->prepare("UPDATE Movie SET title = :title, genre = :genre, runtime = :runtime, language = :language, ageRating = :ageRating, description = :description, imagePath = :imagePath, tagType = :tagType WHERE movieID = :movieID");

        $executeResult = $query->execute([
            ':title' => $title,
            ':genre' => $genre,
            ':runtime' => $runtime,
            ':language' => $language,
            ':ageRating' => $ageRating,
            ':description' => $description,
            ':imagePath' => $fileName,
            ':tagType' => $tagType,
            ':movieID' => $movieID
        ]);

        if ($executeResult) {
            header("Location: movies.php?status=updated");
            exit;
        } else {
            echo "Error updating movie: " . print_r($query->errorInfo(), true);
        }
    }
} elseif (isset($_GET['movieID'])) 
    $movieID = $_GET['movieID'];
    $query = $db->prepare("SELECT * FROM Movie WHERE movieID = :movieID");
    $query->bindParam(':movieID', $movieID, PDO::PARAM_INT);
    $query->execute();
    $movie = $query->fetch(PDO::FETCH_ASSOC);

    if (!$movie) {
        header("Location: movies.php?status=notfound");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit movie</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../includes/admin_styles.css"> <!-- External CSS file for styles -->
</head>
<body>
    <div class="container my-4">
        <div class="form-container mx-auto">
            <h3><i class="material-icons">edit</i> Edit Movie: "<?php echo htmlspecialchars($movie['title']); ?>"</h3>
            <form method="post" action="editMovie.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($movie['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="genre">Genre</label>
                    <input type="text" id="genre" name="genre" class="form-control" value="<?php echo htmlspecialchars($movie['genre']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="runtime">Runtime (minutes)</label>
                    <input type="number" id="runtime" name="runtime" class="form-control" value="<?php echo htmlspecialchars($movie['runtime']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="language">Language</label>
                    <select id="language" name="language" class="form-control" required>
                        <option value="English" <?php if ($movie['language'] == "English") echo "selected"; ?>>English</option>
                        <option value="Danish" <?php if ($movie['language'] == "Danish") echo "selected"; ?>>Danish</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ageRating">Age Rating</label>
                    <input type="text" id="ageRating" name="ageRating" class="form-control" value="<?php echo htmlspecialchars($movie['ageRating']); ?>">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" required><?php echo htmlspecialchars($movie['description']); ?></textarea>
                </div>
                
                <!-- Movie Image Section -->
                <div class="form-group">
                    <label for="movieImage">Change Movie Image</label><br>
                    <?php if (!empty($movie['imagePath'])): ?>
                        <img src="../../includes/media/movies/<?php echo htmlspecialchars($movie['imagePath']); ?>" alt="Current Image" class="preview-image mb-2"><br>
                    <?php endif; ?>
                    <input type="file" name="movieImage" id="movieImage" class="form-control" accept="image/*">
                </div>

                <!-- Tag Type Selection -->
                <div class="form-group">
                    <label for="tagType">Tag Type</label>
                    <select id="tagType" name="tagType" class="form-control" required>
                        <option value="None" <?php if ($movie['tagType'] == "None") echo "selected"; ?>>None</option>
                        <option value="Hot New Movie" <?php if ($movie['tagType'] == "Hot New Movie") echo "selected"; ?>>Hot New Movie</option>
                        <option value="Movie of the Week" <?php if ($movie['tagType'] == "Movie of the Week") echo "selected"; ?>>Movie of the Week</option>
                    </select>
                </div>

                <input type="hidden" name="movieID" value="<?php echo $movieID; ?>">
                <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Modal for replacement confirmation -->
    <?php if ($showModal): ?>
        <div class="modal-container">
            <div class="modal-content">
                <h4>Are you sure you want to replace the movie?</h4>
                <p><strong>Replacing: </strong><?php echo $replaceMovieTitle; ?></p>
                <form action="editMovie.php" method="post">
                    <input type="hidden" name="movieID" value="<?php echo $movieID; ?>">
                    <input type="hidden" name="tagType" value="<?php echo $tagType; ?>">
                    <button type="submit" name="replaceAction" value="yes" class="btn btn-danger">Yes, Replace</button>
                    <a href="movies.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

</body>
</html>
