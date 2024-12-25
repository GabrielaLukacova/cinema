<?php
require_once "../../includes/connection.php";
require_once "../../navbar_footer/cinema_navbar.php";
require_once "../actions/news_logic.php";

// Get the news ID from the URL and fetch the article
$newsID = $_GET['newsID'] ?? null;
$news = getNewsById($newsID);

if (!$news) {
    die("News not found or invalid ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($news['title']); ?></title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<div class="custom-news-container">
    <img src="../../includes/media/news/<?php echo htmlspecialchars($news['imagePath'] ?? 'default.jpg'); ?>" 
         alt="News Image" class="custom-news-image">
    <div class="custom-news-title"><?php echo htmlspecialchars($news['title']); ?></div>
    <div class="custom-news-category"><?php echo htmlspecialchars($news['category']); ?></div>
    <div class="custom-news-article">
        <?php echo nl2br(htmlspecialchars($news['article'])); ?>
    </div>
</div>
</body>
</html>
<?php
require_once '../../navbar_footer/cinema_footer.php';
?>