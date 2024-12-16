<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../actions/movie_single_logic.php"; 
require_once "../../navbar_footer/cinema_navbar.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8'); ?> - Movie Details</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<!-- Hero Section -->
<div class="movie_single_hero" style="background-image: url('../../includes/media/movies/<?= htmlspecialchars($movie['imagePath'], ENT_QUOTES, 'UTF-8'); ?>');">
    <div class="movie_single_overlay">
        <h3><?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
        <p class="movie_single_genre"><?= htmlspecialchars($movie['genre'], ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
</div>

<!-- Movie Info Section -->
<div class="movie_single_info_container">
    <div class="movie_single_info_box movie_single_left_box">
        <p>Language: <?= htmlspecialchars($movie['language'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Runtime: <?= htmlspecialchars($movie['runtime'], ENT_QUOTES, 'UTF-8'); ?> minutes</p>
        <p>Age Rating: <?= htmlspecialchars($movie['ageRating'], ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
    <div class="movie_single_info_box movie_single_right_box">
        <p><?= htmlspecialchars($movie['description'], ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
</div>

    <h2>Pick a date and time</h2>
    
    <div class="date-selection">
        <?php foreach ($availableDates as $date): ?>
            <a href="?movieID=<?= htmlspecialchars($movieID, ENT_QUOTES, 'UTF-8'); ?>&date=<?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?>" 
               class="date-button <?= $date === $selectedDate ? 'active' : ''; ?>">
                <?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?>
            </a>
        <?php endforeach; ?>
    </div>


<?php if (!empty($showtimes)): ?>
    <div class="showtime-selection">
        <?php foreach ($showtimes as $showtime): ?>
            <?php
            // Format the time to show only hours and minutes
            $formattedTime = date('H:i', strtotime($showtime['time']));
            ?>
            <a href="seat_map.php?movieID=<?= htmlspecialchars($movieID, ENT_QUOTES, 'UTF-8'); ?>&showTimeID=<?= htmlspecialchars($showtime['showTimeID'], ENT_QUOTES, 'UTF-8'); ?>">
                <button class="btn-primary"><?= htmlspecialchars($formattedTime, ENT_QUOTES, 'UTF-8'); ?></button>
            </a>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No showtimes available for the selected date.</p>
<?php endif; ?>


<?php require_once "../../navbar_footer/cinema_footer.php"; ?>




