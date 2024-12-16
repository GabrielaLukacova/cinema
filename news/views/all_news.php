<?php 
require_once("../../includes/connection.php"); 
require_once '../../navbar_footer/cinema_navbar.php'; 
require_once "../actions/news_logic.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List of News</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

<div class="hero">
    <img src="../../includes/media/other/news_hero.jpg" alt="">
    <h1>Dream Screen's News Hub - Fresh Updates & More!</h1>
</div>

<div class="news-container">
    <?php foreach ($getNews as $news): ?>
        <div class="news-card" onclick="location.href='news_single.php?newsID=<?php echo $news['newsID']; ?>'">
            <div class="image-container">
                <img src="../../includes/media/news/<?php echo htmlspecialchars($news['imagePath'] ?? 'default.jpg'); ?>" alt="News Image">
            </div>
            <div class="category"><?php echo htmlspecialchars($news['category']); ?></div>
            <div class="title"><?php echo htmlspecialchars($news['title']); ?></div>
        </div>
    <?php endforeach; ?>
</div>