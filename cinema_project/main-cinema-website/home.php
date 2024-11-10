<?php
require_once("../includes/connection.php");
include("cinema_navbar.php");
// include_once 'cinema_navbar.php';
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">



</head>
<body>
    



<?php 

$query = $db->prepare("SELECT title, imagePath, genre, description, movieID, tagType
                       FROM Movie
                       WHERE tagType = 'Movie of the Week' LIMIT 1");
$query->execute();
$movieOfTheWeek = $query->fetch(PDO::FETCH_ASSOC);
echo 'Background image URL: ' . '../includes/media/movies/' . htmlspecialchars($movieOfTheWeek['imagePath']);  // Debug line

?>


<div class="movie_single_hero" style="background-image: url('../includes/media/movies/<?php echo htmlspecialchars($movieOfTheWeek['imagePath'] ?? 'default.jpg'); ?>');">
    <div class="home-hero">
        <p class="tag-name">Movie of the Week</p>
        <h3><?php echo htmlspecialchars($movieOfTheWeek['title']); ?></h3>
        <p class="genre"><?php echo htmlspecialchars($movieOfTheWeek['genre']); ?></p>
        <p class="description">
            <?php
                $shortDescription = implode(' ', array_slice(explode(' ', $movieOfTheWeek['description']), 0, 20)) . '...';
                echo htmlspecialchars($shortDescription);
            ?>
        </p>
        <a href="movie_details.php?movieID=<?php echo $movieOfTheWeek['movieID']; ?>" class="btn btn-secondary">See More</a>
    </div>
</div>

<a href="logout.php" class="btn btn-danger">Logout</a>










<!-- 
            MOVIE CALENDAR
            MOVIE CALENDAR
            MOVIE CALENDAR -->
 

<h2>Movie calendar</h2>
<?php
// Get selected date from URL query parameter, or default to today
$selectedDate = isset($_GET['date']) ? $_GET['date'] : (new DateTime())->format('Y-m-d');

// Function to get movies for a specific date, grouped by movie
function getMoviesForDate($db, $date) {
    $query = $db->prepare("
        SELECT m.movieID, m.title, m.genre, m.runtime, m.ageRating, m.language, m.imagePath, s.time
        FROM Movie m
        JOIN ShowTime s ON m.movieID = s.movieID
        WHERE s.date = :date
        ORDER BY m.movieID, s.time ASC
    ");
    $query->execute([':date' => $date]);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    // Grouping showtimes by movie ID
    $movies = [];
    foreach ($results as $row) {
        $movieID = $row['movieID'];
        if (!isset($movies[$movieID])) {
            // Initialize movie entry with basic info and empty showtimes array
            $movies[$movieID] = [
                'title' => $row['title'],
                'genre' => $row['genre'],
                'runtime' => $row['runtime'],
                'ageRating' => $row['ageRating'],
                'language' => $row['language'],
                'imagePath' => $row['imagePath'],
                'showtimes' => []
            ];
        }
        // Append the showtime to the movie's showtimes array
        $movies[$movieID]['showtimes'][] = $row['time'];
    }

    return $movies;
}

// Function to display the movie calendar
// Function to display the movie calendar
function displayMovieCalendar($db, $selectedDate) {
    $today = new DateTime();

    echo '<div class="calendar-buttons">';
    // Generate buttons for today and the next 5 days
    for ($i = 0; $i < 7; $i++) {
        $date = clone $today;
        $date->modify("+$i days");
        $formattedDate = $date->format('Y-m-d');
        $dayName = ($i === 0) ? "Today" : ($i === 1 ? "Tomorrow" : $date->format('j.n. l'));

        // Highlight the selected date button
        $buttonClass = ($formattedDate === $selectedDate) ? 'calendar-day btn-primary selected' : 'calendar-day btn-primary';
        echo "<a href='?date=$formattedDate' class='$buttonClass'>" . htmlspecialchars($dayName) . "</a>";
    }
    echo '</div>';

    // Get movies for the selected date
    $movies = getMoviesForDate($db, $selectedDate);

    if (empty($movies)) {
        echo "<p class='no-movies'>No movies scheduled for this day.</p>";
    } else {
        foreach ($movies as $movie) {
            echo "<div class='movie-calendar-item'>";
            echo "<div class='movie-calendar-info-container'>";
            echo "<img class='movie-calendar-image' src='../includes/media/movies/" . htmlspecialchars($movie['imagePath']) . "' alt='" . htmlspecialchars($movie['title']) . "' />";
            echo "<div class='movie-calendar-info'>";
            echo "<h4 class='movie-calendar-title'>" . htmlspecialchars($movie['title']) . "</h4>";
            echo "<p class='movie-calendar-details'>" . htmlspecialchars($movie['genre']) . " | " . htmlspecialchars($movie['runtime']) . " min | " . htmlspecialchars($movie['ageRating']) . "+";

            // Display the flag image based on the language if available
            $flagPath = "../includes/media/flags/" . strtolower($movie['language']) . "_flag";
            if (file_exists($flagPath . ".jpg") || file_exists($flagPath . ".png")) {
                $flagExt = file_exists($flagPath . ".jpg") ? "jpg" : "png";
                echo "<img src='" . htmlspecialchars("$flagPath.$flagExt") . "' alt='" . htmlspecialchars($movie['language']) . " Flag' width='20' height='15'>";
            }
            echo "</p>";
            echo "</div>"; // Close movie-calendar-info
            echo "</div>"; // Close movie-calendar-info-container

            // Display showtimes as primary buttons
            echo "<div class='movie-calendar-showtimes-container'>";
            foreach ($movie['showtimes'] as $time) {
                echo "<button class='btn-primary movie-calendar-showtime-button'>" . htmlspecialchars($time) . "</button>";
            }
            echo "</div>"; // Close movie-calendar-showtimes-container
            echo "</div>"; // Close movie-calendar-item
        }
    }
}
?>

<div class="movie-calendar">
    <?php displayMovieCalendar($db, $selectedDate); ?>
</div>

























<section class="location-section">
    <h3>Where You Can Find Us</h3>
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
                <span>Exnersgade 20, 6700 Esbjerg</span>
            </div>
            <p>After entering Broen, head to the main atrium. Take the escalator to the second floor, walk past the food court, and you'll find our cinema entrance.</p>
        </div>
    </div>
</section>

<?php include_once 'cinema_footer.php'; ?>



</body>
</html>



