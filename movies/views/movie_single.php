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
    <div class="overlay-movie-details">
        <h3><?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8'); ?></h3> <br>
        <p class="movie_single_genre"><?= htmlspecialchars($movie['genre'], ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
    </div>
</div>

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


    
    <div class="movie-calendar-single">
    <h2>Pick a date and time</h2>
    <?php
    // Helper function to format the date for display
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
    $futureDates = array_filter($availableDates, function ($date) use ($today) {
        return new DateTime($date) >= $today; // Exclude past dates
    });
    $futureDates = array_slice($futureDates, 0, 7); // Limit to today + next 6 days

    foreach ($futureDates as $date):
        $formattedDate = formatDisplayDate($date, $today);

        // Fetch showtimes for the current date
        $showtimeQuery->execute([':movieID' => $movieID, ':date' => $date]);
        $showtimesForDate = $showtimeQuery->fetchAll(PDO::FETCH_ASSOC);
    ?>
        <div class="movie-calendar-single-item">
            <!-- Display the date -->
            <div class="movie-calendar-single-date">
                <?= htmlspecialchars($formattedDate, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <!-- Display the showtime buttons -->
            <div class="movie-calendar-single-showtimes">
                <?php if (!empty($showtimesForDate)): ?>
                    <?php foreach ($showtimesForDate as $showtime): ?>
                        <?php
                        $formattedTime = date('H:i', strtotime($showtime['time']));
                        ?>
                        <a href="seat_map.php?movieID=<?= htmlspecialchars($movieID, ENT_QUOTES, 'UTF-8'); ?>&showTimeID=<?= htmlspecialchars($showtime['showTimeID'], ENT_QUOTES, 'UTF-8'); ?>">
                            <button class="btn-primary"><?= htmlspecialchars($formattedTime, ENT_QUOTES, 'UTF-8'); ?></button>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span>No showtimes</span>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>






<?php require_once "../../navbar_footer/cinema_footer.php"; ?>




