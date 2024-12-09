<?php
require_once "../includes/connection.php";
require_once "../navbar_footer/cinema_navbar.php";
require_once "../admin/movies/classes/movie.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

$movieHandler = new Movie($db); // Instantiate the Movie class

try {
    $movies = $movieHandler->getAllMovies(); // Fetch all movies
} catch (Exception $e) {
    die("<p>Error fetching movies: " . htmlspecialchars($e->getMessage()) . "</p>");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie List</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="hero">
    <img src="../includes/media/other/all_movies_hero.jpg" alt="All Movies Hero Image">
    <h1>Dream Screen now playing</h1>
</div>

<div class="movies-container">
    <?php if (!empty($movies)): ?>
        <?php foreach ($movies as $movie) : ?>
            <a href="movie_single.php?movieID=<?= htmlspecialchars($movie['movieID'], ENT_QUOTES, 'UTF-8'); ?>" class="movie-card">
                <div class="movie-background" style="background-image: url('../includes/media/movies/<?= htmlspecialchars($movie['imagePath'], ENT_QUOTES, 'UTF-8'); ?>');">
                    <div class="movie-overlay">
                        <div class="movie-title">
                            <h4><?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8'); ?></h4>
                        </div>
                        <div class="movie-info">
                            <p><?= htmlspecialchars($movie['genre'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p><?= htmlspecialchars($movie['runtime'], ENT_QUOTES, 'UTF-8'); ?> min</p>
                            <p><?= htmlspecialchars($movie['ageRating'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p>
                                <?php 
                                    $flagBasePath = "../includes/media/flags/" . strtolower($movie['language']) . "_flag";
                                    $flagPath = null;

                                    if (file_exists($flagBasePath . ".jpg")) {
                                        $flagPath = $flagBasePath . ".jpg";
                                    } elseif (file_exists($flagBasePath . ".png")) {
                                        $flagPath = $flagBasePath . ".png";
                                    }

                                    if ($flagPath): ?>
                                        <img src="<?= htmlspecialchars($flagPath, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($movie['language'], ENT_QUOTES, 'UTF-8'); ?> Flag" width="20" height="15">
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="movie-description">
                            <p><?= htmlspecialchars(substr($movie['description'], 0, 70), ENT_QUOTES, 'UTF-8') . '...'; ?></p>
                        </div>
                        <button class="see-more">See More</button>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No movies available at the moment. Please check back later.</p>
    <?php endif; ?>
</div>

<?php require_once "../navbar_footer/cinema_footer.php"; ?>

