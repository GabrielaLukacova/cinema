<?php 
require_once "../../includes/connection.php"; 
require_once "../components/admin_navbar.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editing News</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../admin_style/admin_style.css?v=1.2">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php
$replaceNewsTitle = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newsID = $_POST['newsID'];
    $title = $_POST['title'];
    $category = $_POST['category'];
    $article = $_POST['article'];

    $query = $db->prepare("SELECT imagePath FROM News WHERE newsID = :newsID");
    $query->bindParam(':newsID', $newsID, PDO::PARAM_INT);
    $query->execute();
    $news = $query->fetch(PDO::FETCH_ASSOC);

    if (!$news) {
        header("Location: news.php?status=notfound");
        exit;
    }

    $fileName = $news['imagePath'];
    if (isset($_FILES['newsImage']) && $_FILES['newsImage']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../../includes/media/news/";
        $fileName = basename($_FILES['newsImage']['name']);
        $targetFilePath = $targetDir . $fileName;
        if (!move_uploaded_file($_FILES['newsImage']['tmp_name'], $targetFilePath)) {
            echo "Error uploading image.";
            exit;
        }
    }

    // Update the news details
    $query = $db->prepare("UPDATE News SET title = :title, category = :category,  article = :article, imagePath = :imagePath WHERE newsID = :newsID");

    $executeResult = $query->execute([
        ':title' => $title,
        ':category' => $category,
        ':article' => $article,
        ':imagePath' => $fileName,
        ':newsID' => $newsID
    ]);

    if ($executeResult) {
        header("Location: news.php?status=updated");
        exit;
    } else {
        echo "Error updating news: " . print_r($query->errorInfo(), true);
    }
} elseif (isset($_GET['newsID'])) {
    $newsID = $_GET['newsID'];
    $query = $db->prepare("SELECT * FROM News WHERE newsID = :newsID");
    $query->bindParam(':newsID', $newsID, PDO::PARAM_INT);
    $query->execute();
    $news = $query->fetch(PDO::FETCH_ASSOC);

    if (!$news) {
        header("Location: news.php?status=notfound");
        exit;
    }
}
?>

<div class="container my-4">
    <div class="form-container mx-auto">
        <h3><i class="material-icons">edit</i> Edit News: "<?php echo htmlspecialchars($news['title']); ?>"</h3>
        <form method="post" action="editNews.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($news['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" class="form-control" required>
                    <option value="Review" <?php if ($news['category'] == "Review") echo "selected"; ?>>Review</option>
                    <option value="Interview" <?php if ($news['category'] == "Interview") echo "selected"; ?>>Interview</option>
                    <option value="Event" <?php if ($news['category'] == "Event") echo "selected"; ?>>Event</option>
                    <option value="Promotion" <?php if ($news['category'] == "Promotion") echo "selected"; ?>>Promotion</option>
                </select>
            </div>
            <div class="form-group">
                <label for="article">Article</label>
                <textarea id="article" name="article" class="form-control" required><?php echo htmlspecialchars($news['article']); ?></textarea>
            </div>
            <!-- News Image -->
            <div class="form-group">
                <label for="newsImage">Change News Image</label><br>
                <?php if (!empty($news['imagePath'])): ?>
                    <img src="../../includes/media/news/<?php echo htmlspecialchars($news['imagePath']); ?>" alt="Current Image" class="preview-image mb-2"><br>
                <?php endif; ?>
                <input type="file" name="newsImage" id="newsImage" class="form-control" accept="image/*">
            </div>
            <input type="hidden" name="newsID" value="<?php echo $newsID; ?>">
            <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
        </form>
    </div>
</div>

</body>
</html>
