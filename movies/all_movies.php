<?php
require_once "../includes/connection.php";
require_once "../navbar_footer/cinema_navbar.php";
require_once "../admin/movies/classes/movie.php";

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
    <link rel="stylesheet" href="../css/style.css">
    <style>
.movies-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 35px;
    padding: 120px 13%;
    margin: 0 auto; 
    max-width: 1200px; 
}

.movie-card {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
    background-size: cover;
    background-position: center;
    height: 350px;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
}

.movie-card:hover {
    transform: scale(1.05);
}

.movie-overlay {
    position: absolute;
    bottom: 0;
    width: 100%;
    background: rgba(0, 0, 0, 0.7);
    color: #fff;
    padding: 10px;
    text-align: center;
    transition: height 0.3s ease, padding-top 0.3s ease;
    height: 60px;
    overflow: hidden; 
    box-sizing: border-box; 
}

.movie-card:hover .movie-overlay {
    height: 100%; 
    padding-top: 15px; 
    display: flex;
    flex-direction: column;
    justify-content: start;
    align-items: center;
}

.movie-title {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 5px;    
    margin-top: 5px;
    transition: padding-top 0.3s ease;
}

.movie-card:hover .movie-title {
    margin-bottom: 10px;
    padding-top: 0; 
}

.movie-info {
    display: none;
    text-align: left; 
    padding: 0 10px; 
}

.movie-card:hover .movie-info {
    display: block;
    font-size: 14px;
    line-height: 1.5;
}

.movie-description {
    overflow: hidden; 
    text-overflow: ellipsis; 
    white-space: nowrap; 
}

.see-more {
    display: none;
    background: #e74c3c;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: auto; 

}

.movie-card:hover .see-more {
    display: block;
}

    </style>
</head>
<body>
<div class="hero">
    <img src="../includes/media/other/all_movies_hero.jpg" alt="All Movies Hero Image">
    <h1>Dream Screen Now Playing</h1>
</div>

<div class="movies-container">
    <?php if (!empty($movies)): ?>
        <?php foreach ($movies as $movie): ?>
            <a href="movie_single.php?movieID=<?= htmlspecialchars($movie['movieID'], ENT_QUOTES, 'UTF-8'); ?>" 
               class="movie-card" 
               style="background-image: url('../includes/media/movies/<?= htmlspecialchars($movie['imagePath'], ENT_QUOTES, 'UTF-8'); ?>');">
                <div class="movie-overlay">
                    <div class="movie-title"><?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="movie-info">
                        <p><?= htmlspecialchars($movie['genre'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><?= htmlspecialchars($movie['runtime'], ENT_QUOTES, 'UTF-8'); ?> min</p>
                        <p><?= htmlspecialchars($movie['ageRating'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p>
                        <?php 
                                // Define base path for the flag image using language
                                $flagBasePath = "../includes/media/flags/" . strtolower($movie['language']) . "_flag";
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
</div>

<?php require_once "../navbar_footer/cinema_footer.php"; ?>
</body>
</html>

