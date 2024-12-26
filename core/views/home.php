<?php
require_once "../../includes/connection.php";
require_once "../../navbar_footer/cinema_navbar.php";
require_once "../../admin/movies/classes/movie.php"; 
require_once "../actions/hero_home_logic.php";
require_once "../actions/hot_new_movies_logic.php";
require_once "../actions/movie_calendar_logic.php";
require_once "../actions/cinema_details_logic.php";
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

    


<!-- HOME HERO -->
<!-- HOME HERO -->
<!-- HOME HERO -->


<section>
    <a href="../../movies/views/movie_single.php?movieID=<?= htmlspecialchars($movieOfTheWeek['movieID'], ENT_QUOTES, 'UTF-8'); ?>" 
       class="movie-link">
        <div class="movie_single_hero" 
             style="background-image: url('../../includes/media/movies/<?= htmlspecialchars($movieOfTheWeek['imagePath'], ENT_QUOTES, 'UTF-8'); ?>');">
            <div class="overlay"></div> <div class="overlay-home-left">
            <div class="home-hero">
                <p class="tag-name">Movie of the week</p>
                <h3><?= htmlspecialchars($movieOfTheWeek['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <p class="genre"><?= htmlspecialchars($movieOfTheWeek['genre'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="description"><?= htmlspecialchars($shortDescription, ENT_QUOTES, 'UTF-8'); ?></p>
                <a href="../../movies/views/movie_single.php?movieID=<?= htmlspecialchars($movieOfTheWeek['movieID'], ENT_QUOTES, 'UTF-8'); ?>" 
                   class="btn btn-secondary">See more</a>
            </div>
        </div>
    </a></div>
</section>




<!-- 
            8 HOT NEW MOVIES 
            8 HOT NEW MOVIES 
            8 HOT NEW MOVIES  -->

            <section class="hot-movies">
    <h2>Hot New Movies</h2>

    <div class="hot-movies-container">
        <?php if (!empty($moviesToShow)): ?>
            <?php foreach ($moviesToShow as $movie): ?>
                <a href="../../movies/views/movie_single.php?movieID=<?= htmlspecialchars($movie['movieID'], ENT_QUOTES, 'UTF-8'); ?>" 
                   class="hot-movie-card" 
                   style="background-image: url('../../includes/media/movies/<?= htmlspecialchars($movie['imagePath'], ENT_QUOTES, 'UTF-8'); ?>');">
                    <div class="movie-overlay">
                        <div class="movie-title"><?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="movie-info">
                            <p><?= htmlspecialchars($movie['genre'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p><?= htmlspecialchars($movie['runtime'], ENT_QUOTES, 'UTF-8'); ?> min</p>
                            <p><?= htmlspecialchars($movie['ageRating'], ENT_QUOTES, 'UTF-8'); ?>+</p>
                            <p>
                                <?php 
                                // Define base path for the flag image using language
                                $flagBasePath = "../../includes/media/flags/" . strtolower($movie['language']) . "_flag";
                                $flagPath = null;

                                // Check both .jpg and .png formats
                                if (file_exists($flagBasePath . ".jpg")) {
                                    $flagPath = $flagBasePath . ".jpg";
                                } elseif (file_exists($flagBasePath . ".png")) {
                                    $flagPath = $flagBasePath . ".png";
                                }

                                // Display the flag image if found
                                if ($flagPath): ?>
                                    <img src="<?= htmlspecialchars($flagPath, ENT_QUOTES, 'UTF-8'); ?>" 
                                         alt="<?= htmlspecialchars($movie['language'], ENT_QUOTES, 'UTF-8'); ?> Flag" 
                                         width="20" height="15">
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

    <!-- Horizontal scroll bar below movies -->
    <div class="movie-scroller">
        <?php for ($i = 1; $i <= $totalSections; $i++): ?>
            <a href="?section=<?= $i; ?>" 
               class="movie-scroll-bar <?= $i === $currentSection ? 'active' : ''; ?>"></a>
        <?php endfor; ?>
    </div>
</section>



<!-- 
            MOVIE CALENDAR
            MOVIE CALENDAR
            MOVIE CALENDAR -->
 
           
<div class="movie-calendar">
    <h2>Movie Calendar</h2>
    <?php displayMovieCalendar($db, $selectedDate); ?>
</div>



<!-- ABOUT CINEMA -->
<!-- ABOUT CINEMA -->
<!-- ABOUT CINEMA -->

<section class="cinema-section">
    <h2>About <?= htmlspecialchars($cinema['name'] ?? 'Our Cinema'); ?></h2>

    <div class="cinema-description">
        <div class="cinema-slogan">
            <p>See the magic, <br> feel the story</p>
        </div>
        <div class="cinema-info">
            <p><?= nl2br(htmlspecialchars($cinema['description'] ?? 'Cinema details are not available at the moment.')); ?></p>
        </div>
    </div>
</section>

<!-- Parallax Image Section -->
<div class="parallax-container">
    <div class="parallax-image-cinema"></div>
</div>

<!-- Location Section -->
<section class="location-section">
    <h3>Where you can find us</h3>
    <div class="location-content">
        <div class="location-map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2064.238613009921!2d8.454900972179242!3d55.46539955973912!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x464b26d2d038d65f%3A0x8c055943a3885780!2sBROEN%20Shopping!5e1!3m2!1sen!2sdk!4v1730402391345!5m2!1sen!2sdk" 
                width="100%" 
                height="300" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
        <div class="location-info">
            <div class="location-details">
                <span class="material-icons location-icon">place</span>
                <span>
                    <?= htmlspecialchars($cinema['street'] ?? 'Address not available'); ?>, 
                    <?= htmlspecialchars($cinema['postalCode'] ?? ''); ?> 
                    <?= htmlspecialchars($cinema['city'] ?? ''); ?>
                </span>
            </div>
            <p>After entering Shopping center Broen, head to the main atrium. Take the escalator to the second floor, walk past the food court, and you'll find our cinema entrance.</p>
        </div>
    </div>
</section>

<!-- Opening Hours Section -->
<section>
    <div class="opening-hours-container">
        <div class="opening-hours">
            <div class="opening-hours-text">
                <h2>Opening Hours</h2>
                <?php if (!empty($openingHours)): ?>
                    <?php foreach ($openingHours as $hour): ?>
                        <div class="day">
                            <span><?= htmlspecialchars($hour['dayOfWeek']); ?></span>
                            <span>
                                <?= htmlspecialchars(date("H:i", strtotime($hour['openingTime']))) . 
                                   ' - ' . 
                                   htmlspecialchars(date("H:i", strtotime($hour['closingTime']))); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No opening hours available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
















<?php require_once "../../navbar_footer/cinema_footer.php"; ?>



