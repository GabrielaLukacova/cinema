<?php
require_once("../../includes/connection.php");

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $article = $_POST['article'];
    $fileName = null;

    // image upload
    if (isset($_FILES['newsImage']) && $_FILES['newsImage']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../../includes/media/news/";
        $fileName = basename($_FILES['newsImage']['name']);
        $targetFilePath = $targetDir . $fileName;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['newsImage']['type'], $allowedTypes)) {
            echo "<div class='alert alert-danger'>Invalid file type. Only JPG, PNG, and GIF are allowed.</div>";
            exit;
        }

        if (!move_uploaded_file($_FILES['newsImage']['tmp_name'], $targetFilePath)) {
            echo "<div class='alert alert-danger'>Error uploading image.</div>";
            exit;
        }
    }

    // Insert News Data into the Database
    $query = $db->prepare("INSERT INTO News (title, category, article, imagePath) 
                           VALUES (:title, :category, :article, :imagePath)");

    $executeResult = $query->execute([
        ':title' => $title,
        ':category' => $category,
        ':article' => $article,
        ':imagePath' => $fileName 
    ]);

    if ($executeResult) {
        header("Location: news.php?status=added");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error adding news: ";
        print_r($query->errorInfo());
        echo "</div>";
    }
} else {
    header("Location: news.php?status=0");
    exit;
}
?>