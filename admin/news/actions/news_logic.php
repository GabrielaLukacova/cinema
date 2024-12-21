<?php
require_once "../../../includes/connection.php";

try {
    // Fetch all news records
    $query = $db->prepare("SELECT * FROM News ORDER BY newsID DESC");
    $query->execute();
    $newsList = $query->fetchAll(PDO::FETCH_ASSOC);

    // Handle form submission for adding new news
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title']);
        $category = trim($_POST['category']);
        $article = trim($_POST['article']);
        $imagePath = null;

        // Handle file upload
        if (!empty($_FILES['newsImage']['name'])) {
            $uploadDir = '../../../includes/media/news/';
            $fileName = basename($_FILES['newsImage']['name']);
            $targetFilePath = $uploadDir . $fileName;

            // Check for valid image file
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($fileExtension, $allowedExtensions)) {
                if (move_uploaded_file($_FILES['newsImage']['tmp_name'], $targetFilePath)) {
                    $imagePath = $fileName;
                } else {
                    throw new Exception("Failed to upload the image.");
                }
            } else {
                throw new Exception("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
            }
        }

        // Insert new news into the database
        $insertQuery = $db->prepare("
            INSERT INTO News (title, category, article, imagePath) 
            VALUES (:title, :category, :article, :imagePath)
        ");
        $insertQuery->execute([
            ':title' => $title,
            ':category' => $category,
            ':article' => $article,
            ':imagePath' => $imagePath
        ]);

        // Redirect with success message
        header("Location: ../views/news_list.php?status=News added successfully.");
        exit();
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    die("<div class='alert alert-danger'>An error occurred: " . htmlspecialchars($e->getMessage()) . "</div>");
}
