<?php
require_once "../../../includes/connection.php";

try {
    $news = null;

    // form submission for updating news
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newsID = $_POST['newsID'] ?? null;
        $title = trim($_POST['title']);
        $category = trim($_POST['category']);
        $article = trim($_POST['article']);
        $fileName = null;

        if (!$newsID) {
            throw new Exception("Invalid news ID.");
        }

        // fetch existing news to retrieve the current image path
        $query = $db->prepare("SELECT imagePath FROM News WHERE newsID = :newsID");
        $query->execute([':newsID' => $newsID]);
        $news = $query->fetch(PDO::FETCH_ASSOC);

        if (!$news) {
            header("Location: ../views/news_list.php?status=notfound");
            exit();
        }

        $fileName = $news['imagePath'];

        // handle image upload if a new file is provided
        if (isset($_FILES['newsImage']) && $_FILES['newsImage']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../../includes/media/news/';
            $fileName = basename($_FILES['newsImage']['name']);
            $targetFilePath = $uploadDir . $fileName;

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedExtensions)) {
                throw new Exception("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
            }

            if (!move_uploaded_file($_FILES['newsImage']['tmp_name'], $targetFilePath)) {
                throw new Exception("Failed to upload the image.");
            }
        }

        // Update the news details
        $updateQuery = $db->prepare("
            UPDATE News 
            SET title = :title, category = :category, article = :article, imagePath = :imagePath 
            WHERE newsID = :newsID
        ");
        $updateQuery->execute([
            ':title' => $title,
            ':category' => $category,
            ':article' => $article,
            ':imagePath' => $fileName,
            ':newsID' => $newsID
        ]);

        header("Location: ../views/news_list.php?status=updated");
        exit();
    }

    // Handle GET request to fetch news for editing
    if (isset($_GET['newsID'])) {
        $newsID = $_GET['newsID'];
        $query = $db->prepare("SELECT * FROM News WHERE newsID = :newsID");
        $query->execute([':newsID' => $newsID]);
        $news = $query->fetch(PDO::FETCH_ASSOC);

        if (!$news) {
            header("Location: ../views/news_list.php?status=notfound");
            exit();
        }
    } else {
        throw new Exception("News ID not provided.");
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    die("<div class='alert alert-danger'>An error occurred: " . htmlspecialchars($e->getMessage()) . "</div>");
}
?>
