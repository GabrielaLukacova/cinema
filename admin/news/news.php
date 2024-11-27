<?php 
require_once("../../includes/connection.php"); 
include '../includes/admin_navbar.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Managing news</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../includes/admin_style.css?v=1.2">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php
// Fetch news from the database
$query = $db->prepare("SELECT * FROM News");
$query->execute();
$getNews = $query->fetchAll();
?>


<div class="container my-7">
    
<!-- Button to Scroll to Form -->
<div class="container my-4 text-end">
    <a href="#addNewsForm" class="btn btn-success btn-lg">+ New news</a>
</div>

    <h2 class="text-center mb-5"> Manage news</h2>

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
                    <th>News ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($getNews as $news): ?>
                    <tr class="news-card">
                        <td><?php echo htmlspecialchars($news['newsID']?? ''); ?></td>
                        <td><?php echo htmlspecialchars($news['title']?? ''); ?></td>
                        <td><?php echo htmlspecialchars($news['category']?? ''); ?></td>
                        <td>
                            <img src="../../includes/media/news/<?php echo htmlspecialchars($news['imagePath']?? ''); ?>" alt="News Image" class="movie-image">
                        </td>                        <td>
                            <a href="editNews.php?newsID=<?php echo $news['newsID']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="deleteNews.php?newsID=<?php echo $news['newsID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this news?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h3 class="mt-5">Add new news</h3>
    <form id="addNewsForm" method="post" action="addNews.php" enctype="multipart/form-data" class="p-4 shadow rounded bg-white">
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
               <button type="submit" name="submit" class="btn btn-success btn-block">
        + Add news
        </button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
