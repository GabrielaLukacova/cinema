<?php
require_once "../includes/connection.php";
require_once "../navbar_footer/cinema_navbar.php";


// Get the news ID from the URL
if (!isset($_GET['newsID']) || !is_numeric($_GET['newsID'])) {
    die("Invalid news ID");
}

$newsID = intval($_GET['newsID']);

// Fetch the specific news article
$query = $db->prepare("SELECT * FROM News WHERE newsID = :newsID");
$query->execute([':newsID' => $newsID]);
$news = $query->fetch();

if (!$news) {
    die("News not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($news['title']); ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="custom-news-container">
    <img src="../includes/media/news/<?php echo htmlspecialchars($news['imagePath'] ?? 'default.jpg'); ?>" 
         alt="News Image" class="custom-news-image">
    <div class="custom-news-title"><?php echo htmlspecialchars($news['title']); ?></div>
    <div class="custom-news-category"><?php echo htmlspecialchars($news['category']); ?></div>
    <div class="custom-news-article">
        <?php echo nl2br(htmlspecialchars($news['article'])); ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
