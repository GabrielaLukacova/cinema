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
    <style>
        .news-container {
            /* max-width: 800px; */
            padding: 0 12%;
            /* margin: 50px auto; */
            /* text-align: center; */
            
        }

        .news-category {
            color: #CB4540;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .news-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .news-image {
            width: 100%;
            
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            max-height: 500px;
        }

        .news-article {
            font-size: 1.1rem;
            line-height: 1.8;
            text-align: justify;
        }
    </style>
</head>
<body>
<div class="news-container">
    <div class="news-title"><?php echo htmlspecialchars($news['title']); ?></div>
    <div class="news-category"><?php echo htmlspecialchars($news['category']); ?></div>
    <img src="../includes/media/news/<?php echo htmlspecialchars($news['imagePath'] ?? 'default.jpg'); ?>" 
         alt="News Image" class="news-image">
    <div class="news-article">
        <?php echo nl2br(htmlspecialchars($news['article'])); ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
