<?php 
require_once "../../components/views/admin_navbar.php"; 
require_once "../actions/news_logic.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage news</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../../admin_style/admin_style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container my-4">
    <!-- Add news button -->
    <div class="text-end mb-4">
        <a href="#addNewsForm" class="btn btn-success btn-lg">+ New news</a>
    </div>

    <!-- Success message -->
    <?php if (isset($_GET['status'])): ?>
        <div class="alert alert-info">
            <i class="material-icons btn-icon">info</i>
            <?= htmlspecialchars($_GET['status']); ?>
        </div>
    <?php endif; ?>

    <!-- news table -->
    <h2 class="text-center mb-5"> 📰 Manage news</h2>
        <table class="table p-4 shadow rounded bg-white">
            <thead>
                <tr>
                    <th>News ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($newsList as $news): ?>
                    <tr>
                        <td><?= htmlspecialchars($news['newsID']); ?></td>
                        <td><?= htmlspecialchars($news['title']); ?></td>
                        <td><?= htmlspecialchars($news['category']); ?></td>
                        <td>
                            <?php if (!empty($news['imagePath'])): ?>
                                <img src="../../../includes/media/news/<?= htmlspecialchars($news['imagePath']); ?>" alt="News Image"
                                     class="img-thumbnail" 
                                     style="max-width: 100px;">
                            <?php else: ?>
                                <p>No image</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="../views/edit_news_view.php?newsID=<?= $news['newsID']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="../actions/delete_news.php?newsID=<?= $news['newsID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this news?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <!-- Add News Form -->
    <h3 id="addNewsForm" class="mt-5">Add new news</h3>
    <form method="post" enctype="multipart/form-data" class="p-4 shadow rounded bg-white">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" class="form-control" required>
                <option value="Review">Review</option>
                <option value="Interview">Interview</option>
                <option value="Event">Event</option>
                <option value="Promotion">Promotion</option>
            </select>
        </div>
        <div class="form-group">
            <label for="article">Article</label>
            <textarea id="article" name="article" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="newsImage">News image</label>
            <input type="file" name="newsImage" id="newsImage" class="form-control-file" accept="image/*">
        </div>
        <button type="submit" class="btn btn-success btn-block">+ Add news</button>
    </form>
</div>
</body>
</html>
