<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../../includes/connection.php";
require_once "../../navbar_footer/cinema_navbar.php";
require_once "../../admin/movies/classes/movie.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Instantiate the Movie class
$movieHandler = new Movie($db);

try {
    $movies = $movieHandler->getAllMovies(); // Fetch all movies
} catch (Exception $e) {
    die("<p>Error fetching movies: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Movies</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<div class="hero">
    <img src="../../includes/media/other/all_movies_hero.jpg" alt="All Movies Hero Image">
    <div class="overlay"></div>   
<h1>Dream Screen Now Playing</h1>

</div>


<div class="movies-container">
    <?php if (!empty($movies)): ?>
        <?php foreach ($movies as $movie): ?>
            <a href="movie_single.php?movieID=<?= htmlspecialchars($movie['movieID'], ENT_QUOTES, 'UTF-8'); ?>" 
               class="movie-card" 
               style="background-image: url('../../includes/media/movies/<?= htmlspecialchars($movie['imagePath'], ENT_QUOTES, 'UTF-8'); ?>');">
                <div class="movie-overlay">
                    <div class="movie-title"><?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="movie-info">
                        <p><?= htmlspecialchars($movie['genre'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><?= htmlspecialchars($movie['runtime'], ENT_QUOTES, 'UTF-8'); ?> min</p>
                        <p><?= htmlspecialchars($movie['ageRating'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p>
                        <?php 
                                // Define base path for the flag image using language
                                $flagBasePath = "../../includes/media/flags/" . strtolower($movie['language']) . "_flag";
                                $flagPath = null;

                                //both .jpg and .png formats
                                if (file_exists($flagBasePath . ".jpg")) {
                                    $flagPath = $flagBasePath . ".jpg";
                                } elseif (file_exists($flagBasePath . ".png")) {
                                    $flagPath = $flagBasePath . ".png";
                                }

                                // Display the flag image if found
                                if ($flagPath): ?>
                                    <img src="<?php echo htmlspecialchars($flagPath); ?>" alt="<?php echo htmlspecialchars($movie['language']); ?> Flag" width="20" height="15">
                            <?php endif; ?>
                        </p>
                        <p><?= htmlspecialchars(substr($movie['description'], 0, 90), ENT_QUOTES, 'UTF-8'); ?>...</p>
                        <button class="see-more">See More</button>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No movies available at the moment. Please check back later.</p>
    <?php endif; ?>
</div><div class="popcorn-bg">

<?php require_once "../../navbar_footer/cinema_footer.php"; ?>
</body>
</html>

