<?php
require_once("../includes/connection.php");
include("cinema_navbar.php");


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



if (isset($_GET['movieID'])) {
    $movieID = $_GET['movieID'];

    try {
        // Fetch details for the specified movie
        $query = $db->prepare("SELECT title, genre, runtime, language, ageRating, description, imagePath FROM Movie WHERE movieID = :movieID");
        $query->bindParam(':movieID', $movieID, PDO::PARAM_INT);
        $query->execute();
        $movie = $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching movie details: " . $e->getMessage();
        exit();
    }
} else {
    echo "Movie ID not specified.";
    exit();
}

if (!$movie) {
    echo "Movie not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($movie['title']); ?> - Movie Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php if (isset($movie)) : ?>
    <div class="movie_single_hero" style="background-image: url('../includes/media/movies/<?php echo htmlspecialchars($movie['imagePath'] ?? 'default.jpg'); ?>');">
        <div class="movie_single_overlay">
            <p class="movie_single_genre"><?php echo htmlspecialchars($movie['genre']); ?></p>
            <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
        </div>
    </div>

    <div class="movie_single_info_container">
        <div class="movie_single_info_box movie_single_left_box">
            <p>Runtime: <?php echo htmlspecialchars($movie['runtime']); ?> minutes</p>
            <p>Age Rating: <?php echo htmlspecialchars($movie['ageRating']); ?>+</p>
            <p><strong>Language:</strong> 
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
        </div>
        <div class="movie_single_info_box movie_single_right_box">
            <p><?php echo htmlspecialchars($movie['description']); ?></p>
        </div>
    </div>
<?php endif; ?>
















    <?php
// Get specific movie ID from the URL
$movieID = isset($_GET['movieID']) ? (int)$_GET['movieID'] : 0;

// Get selected movie details and showtimes for the upcoming days
function getMovieDetailsAndShowtimes($db, $movieID) {
    $today = new DateTime();
    $dates = [];
    
    for ($i = 0; $i < 7; $i++) {
        $date = clone $today;
        $date->modify("+$i days");
        $formattedDate = $date->format('Y-m-d');
        $dates[] = $formattedDate;
    }

    $query = $db->prepare("
        SELECT m.title, m.genre, m.runtime, m.ageRating, m.language, m.imagePath, s.date, s.time
        FROM Movie m
        JOIN ShowTime s ON m.movieID = s.movieID
        WHERE m.movieID = :movieID AND s.date IN ('" . implode("','", $dates) . "')
        ORDER BY s.date ASC, s.time ASC
    ");
    $query->execute([':movieID' => $movieID]);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    // Group showtimes by date
    $movieDetails = [];
    foreach ($results as $row) {
        $date = $row['date'];
        if (!isset($movieDetails[$date])) {
            $movieDetails[$date] = [
                'title' => $row['title'],
                'genre' => $row['genre'],
                'runtime' => $row['runtime'],
                'ageRating' => $row['ageRating'],
                'language' => $row['language'],
                'imagePath' => $row['imagePath'],
                'showtimes' => []
            ];
        }
        $movieDetails[$date]['showtimes'][] = $row['time'];
    }

    return $movieDetails;
}
 
// Fetch movie details and showtimes
$movieDetails = getMovieDetailsAndShowtimes($db, $movieID);
?>

<div class="movie-calendar-single">
<h2>Pick date and time</h2>
    <?php foreach ($movieDetails as $date => $details): ?>
        <div class="movie-calendar-single-item">
            <div class="movie-calendar-single-date">
                <?php
                // Display 'Today' and 'Tomorrow' for the first two dates
                $dateObj = new DateTime($date);
                $dayText = ($dateObj->format('Y-m-d') == (new DateTime())->format('Y-m-d')) ? "Today" : 
                           (($dateObj->format('Y-m-d') == (new DateTime())->modify('+1 day')->format('Y-m-d')) ? "Tomorrow" : 
                           $dateObj->format('j.n. l'));
                echo htmlspecialchars($dayText);
                ?>
            </div>
            <div class="movie-calendar-single-showtimes">
                <?php foreach ($details['showtimes'] as $time): ?>
                    <button class="btn-primary movie-calendar-showtime-button"><?php echo htmlspecialchars($time); ?></button>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>



<?php include_once 'cinema_footer.php'; ?>

</body>
</html>
