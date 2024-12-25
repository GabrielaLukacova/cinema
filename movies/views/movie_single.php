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
    <div class="overlay">
        <h3><?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8'); ?></h3> <br>
        <p class="movie_single_genre"><?= htmlspecialchars($movie['genre'], ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
</div>

<!-- Movie Info Section -->
<div class="movie_single_info_container">
    <div class="movie_single_info_box movie_single_left_box">
        <p>Language: <?= htmlspecialchars($movie['language'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Runtime: <?= htmlspecialchars($movie['runtime'], ENT_QUOTES, 'UTF-8'); ?> minutes</p>
        <p>Age Rating: <?= htmlspecialchars($movie['ageRating'], ENT_QUOTES, 'UTF-8'); ?>+</p>
    </div>
    <div class="movie_single_info_box movie_single_right_box">
        <p><?= htmlspecialchars($movie['description'], ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
</div>

    <h2>Pick a date and time</h2>

    <div class="calendar-buttons">
    <?php
    $today = new DateTime();

    for ($i = 0; $i < 7; $i++) {
        $date = clone $today;
        $date->modify("+$i days");
        $formattedDate = $date->format('Y-m-d');
        $dayName = ($i === 0) ? "Today" : ($i === 1 ? "Tomorrow" : $date->format('j.n. l'));

        $buttonClass = ($formattedDate === $selectedDate) ? 'calendar-day btn-primary selected' : 'calendar-day btn-primary';

        // Add anchor to ensure scrolling to the showtimes section
        echo "<a href='?movieID=" . htmlspecialchars($movieID, ENT_QUOTES, 'UTF-8') . "&date=$formattedDate#showtime-calendar' class='$buttonClass'>" . htmlspecialchars($dayName, ENT_QUOTES, 'UTF-8') . "</a>";
    }
    ?>
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




<div class="movie-calendar-single">
    <?php if (!empty($availableDates)): ?>
        <?php 
        // Helper function to format the date
        function formatDisplayDate($date, $today) {
            $diff = (new DateTime($date))->diff($today)->days;
            if ($diff === 0) {
                return "Today";
            } elseif ($diff === 1) {
                return "Tomorrow";
            } else {
                return (new DateTime($date))->format('l, j.n.');
            }
        }

        $today = new DateTime();
        foreach ($availableDates as $date):
            $formattedDate = formatDisplayDate($date, $today);
        ?>
            <div class="movie-calendar-single-item">
                <!-- Date section -->
                <div class="movie-calendar-single-date">
                    <?= htmlspecialchars($formattedDate, ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <!-- Showtime buttons -->
                <div class="movie-calendar-single-showtimes">
                    <?php if (!empty($showtimes)): ?>
                        <?php foreach ($showtimes as $showtime): ?>
                            <?php if ($showtime['date'] === $date): ?>
                                <?php
                                $formattedTime = date('H:i', strtotime($showtime['time']));
                                ?>
                                <a href="seat_map.php?movieID=<?= htmlspecialchars($movieID, ENT_QUOTES, 'UTF-8'); ?>&showTimeID=<?= htmlspecialchars($showtime['showTimeID'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <button class="btn-primary"><?= htmlspecialchars($formattedTime, ENT_QUOTES, 'UTF-8'); ?></button>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-showtimes">No showtimes available</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No available dates for this movie.</p>
    <?php endif; ?>
</div>
<?php if (!empty($showtimes)): ?>
    <div class="showtime-selection">
        <?php foreach ($showtimes as $showtime): ?>
            <?php
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




