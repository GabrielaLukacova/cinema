<?php 
require_once "../../includes/connection.php"; 


if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $runtime = $_POST['runtime'];
    $language = $_POST['language'];
    $ageRating = $_POST['ageRating'];
    $description = $_POST['description'];
    $movieTag = $_POST['tag'];

    // File upload handling
    $fileName = null;
    if (isset($_FILES['movieImage']) && $_FILES['movieImage']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../../includes/media/movies/";
        $fileName = basename($_FILES['movieImage']['name']);
        $targetFilePath = $targetDir . $fileName;

        if (!move_uploaded_file($_FILES['movieImage']['tmp_name'], $targetFilePath)) {
            echo "<div class='alert alert-danger'>Error uploading image.</div>";
            exit;
        }
    }
    
    // Insert movie data into the database
    $query = $db->prepare("INSERT INTO Movie (title, genre, runtime, language, ageRating, description, imagePath, movieTag) 
                           VALUES (:title, :genre, :runtime, :language, :ageRating, :description, :imagePath, :movieTag)");

    $executeResult = $query->execute([
        ':title' => $title,
        ':genre' => $genre,
        ':runtime' => $runtime,
        ':language' => $language,
        ':ageRating' => $ageRating,
        ':description' => $description,
        ':imagePath' => $fileName,
        ':movieTag' => $tag
    ]);

    if ($executeResult) {
        header("Location: movies.php?status=added");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error adding movie: ";
        print_r($query->errorInfo());
        echo "</div>";
    }
} else {
    header("Location: movies.php?status=0");
    exit;
}
?>