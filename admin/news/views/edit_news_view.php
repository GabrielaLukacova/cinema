<?php 
require_once "../actions/edit_news.php"; 
require_once "../../components/views/admin_navbar.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit News</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../admin_style/style.css">
</head>
<body>
<div class="container my-4">
    <div class="form-container mx-auto">
        <h3><i class="material-icons">edit</i> Edit News: "<?= htmlspecialchars($news['title']); ?>"</h3>
        <form method="post" action="edit_news_view.php" enctype="multipart/form-data" class="p-4 shadow bg-white">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($news['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" class="form-control" required>
                    <option value="Review" <?= $news['category'] == "Review" ? "selected" : ""; ?>>Review</option>
                    <option value="Interview" <?= $news['category'] == "Interview" ? "selected" : ""; ?>>Interview</option>
                    <option value="Event" <?= $news['category'] == "Event" ? "selected" : ""; ?>>Event</option>
                    <option value="Promotion" <?= $news['category'] == "Promotion" ? "selected" : ""; ?>>Promotion</option>
                </select>
            </div>
            <div class="form-group">
                <label for="article">Article</label>
                <textarea id="article" name="article" class="form-control" rows="5" required><?= htmlspecialchars($news['article']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="newsImage">Change News Image</label><br>
                <?php if (!empty($news['imagePath'])): ?>
                    <img src="../../../includes/media/news/<?= htmlspecialchars($news['imagePath']); ?>" alt="Current Image" class="preview-image mb-3"><br>
                <?php endif; ?>
                <input type="file" name="newsImage" id="newsImage" class="form-control" accept="image/*">
            </div>
            <input type="hidden" name="newsID" value="<?= htmlspecialchars($newsID); ?>">
            <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
        </form>
    </div>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
