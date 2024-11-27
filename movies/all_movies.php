<?php
require_once "../includes/connection.php";
require_once "../navbar_footer/cinema_navbar.php";


ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $query = $db->prepare("SELECT movieID, title, genre, runtime, language, ageRating, description, imagePath FROM Movie");
    $query->execute();
    $movies = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching movies: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movie List</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<div class="hero">
    <img src="../includes/media/other/all_movies_hero.jpg"  style="background-image" alt="">
    <h1>Dream Screen now playing</h1>
</div>



<div class="movies-container">
    <?php foreach ($movies as $movie) : ?>
        <a href="movie_single.php?movieID=<?php echo $movie['movieID']; ?>" class="movie-card">
            <div class="movie-background" style="background-image: url('../includes/media/movies/<?php echo htmlspecialchars($movie['imagePath']); ?>');">
                <div class="movie-overlay">
                    <div class="movie-title">
                        <h4><?php echo htmlspecialchars($movie['title']); ?></h4>
                    </div>
                    <div class="movie-info">
                        <p><?php echo htmlspecialchars($movie['genre']); ?></p>
                        <p><?php echo htmlspecialchars($movie['runtime']); ?> min</p>
                        <p><?php echo htmlspecialchars($movie['ageRating']); ?></p>
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
                                    <img src="<?php echo htmlspecialchars($flagPath); ?>" alt="<?php echo htmlspecialchars($movie['language']); ?> Flag" width="20" height="15">
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="movie-description">
                        <p><?php echo htmlspecialchars(substr($movie['description'], 0, 70)) . '...'; ?></p>
                    </div>
                    <button class="see-more">See More</button>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</div>


<?php 
require_once "../navbar_footer/cinema_footer.php";
?>
