<?php
require_once "../../includes/connection.php";
require_once "../../navbar_footer/cinema_navbar.php";
require_once "../../admin/movies/classes/movie.php"; 
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

<?php 

$query = $db->prepare("SELECT title, imagePath, genre, description, movieID, movieTag
                       FROM Movie
                       WHERE movieTag = 'Movie of the Week' LIMIT 1");
$query->execute();
$movieOfTheWeek = $query->fetch(PDO::FETCH_ASSOC);
?>



<section>
<div class="movie_single_hero" style="background-image: url('../../includes/media/movies/<?php echo htmlspecialchars($movieOfTheWeek['imagePath'] ?? 'default.jpg'); ?>');">
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
</section>








<!-- 
            8 HOT NEW MOVIES 
            8 HOT NEW MOVIES 
            8 HOT NEW MOVIES  -->

            <?php
$movieHandler = new Movie($db);

try {
    // Fetch all movies tagged as "Hot New Movie"
    $movies = $movieHandler->getMoviesByTag("Hot New Movie");
} catch (Exception $e) {
    $movies = []; // Fallback to an empty array if fetching fails
    error_log("Error fetching movies: " . $e->getMessage());
}

// Pagination logic
$moviesPerSection = 5; // Show 5 movies at a time
$totalMovies = count($movies);
$totalSections = ceil($totalMovies / $moviesPerSection);

// Get the current section from the query string
$currentSection = isset($_GET['section']) ? (int)$_GET['section'] : 1;
$currentSection = max(1, min($currentSection, $totalSections)); // Clamp value between 1 and $totalSections

// Determine the slice of movies to show
$startIndex = ($currentSection - 1) * $moviesPerSection;
$moviesToShow = array_slice($movies, $startIndex, $moviesPerSection);
?>



<section class="hot-movies">
    <h2>Hot New Movies</h2>
 
<div class="hot-movies-container">
    <?php if (!empty($movies)): ?>
        <?php foreach ($movies as $movie): ?>
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
 
            <h2>Movie Calendar</h2>
<?php
// Get selected date from URL query parameter, or default to today
$selectedDate = isset($_GET['date']) ? $_GET['date'] : (new DateTime())->format('Y-m-d');

// Function to get movies for a specific date, grouped by movie
function getMoviesForDate($db, $date) {
    $query = $db->prepare("
        SELECT m.movieID, m.title, m.genre, m.runtime, m.ageRating, m.language, m.imagePath, s.showTimeID, s.time
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
        $movies[$movieID]['showtimes'][] = [
            'showTimeID' => $row['showTimeID'],
            'time' => $row['time']
        ];
    }

    return $movies;
}

function displayMovieCalendar($db, $selectedDate) {
    $today = new DateTime();

    echo '<div class="calendar-buttons">';
    // Buttons for today, tomorrow, and the next days
    for ($i = 0; $i < 7; $i++) {
        $date = clone $today;
        $date->modify("+$i days");
        $formattedDate = $date->format('Y-m-d');
        $dayName = ($i === 0) ? "Today" : ($i === 1 ? "Tomorrow" : $date->format('j.n. l'));

        // Highlight the selected date button
        $buttonClass = ($formattedDate === $selectedDate) ? 'calendar-day btn-primary selected' : 'calendar-day btn-primary';
        echo "<a href='?date=$formattedDate' class='$buttonClass'>" . htmlspecialchars($dayName, ENT_QUOTES, 'UTF-8') . "</a>";
    }
    echo '</div>';

    // Movies for the selected date
    $movies = getMoviesForDate($db, $selectedDate);

    if (empty($movies)) {
        echo "<p class='no-movies'>No movies scheduled for this day.</p>";
    } else {
        echo '<div class="movie-calendar-container">';
        foreach ($movies as $movieID => $movie) {
            echo "<a href='../../movies/views/movie_single.php?movieID=" . htmlspecialchars($movieID, ENT_QUOTES, 'UTF-8') . "' class='movie-calendar-item'>";
            echo "<div class='movie-calendar-info-container'>";
            echo "<img class='movie-calendar-image' src='../../includes/media/movies/" . htmlspecialchars($movie['imagePath'], ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8') . "' />";
            echo "<div class='movie-calendar-info'>";
            echo "<h4 class='movie-calendar-title'>" . htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8') . "</h4>";
            echo "<p class='movie-calendar-details'>" . htmlspecialchars($movie['genre'], ENT_QUOTES, 'UTF-8') . " | " . htmlspecialchars($movie['runtime'], ENT_QUOTES, 'UTF-8') . " min | " . htmlspecialchars($movie['ageRating'], ENT_QUOTES, 'UTF-8') . "+";

            $flagPath = "../../includes/media/flags/" . strtolower($movie['language']) . "_flag";
            if (file_exists($flagPath . ".jpg") || file_exists($flagPath . ".png")) {
                $flagExt = file_exists($flagPath . ".jpg") ? "jpg" : "png";
                echo "<img src='" . htmlspecialchars("$flagPath.$flagExt", ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($movie['language'], ENT_QUOTES, 'UTF-8') . " Flag' width='20' height='15'>";
            }
            echo "</p>";
            echo "</div>"; 
            echo "</div>"; 

            // Generate showtime buttons
            echo "<div class='movie-calendar-showtimes-container'>";
            foreach ($movie['showtimes'] as $showtime) {
                // Format the time to show only hours and minutes
                $formattedTime = date('H:i', strtotime($showtime['time']));
                
                echo "<a href='../../movies/views/seat_map.php?movieID=" . htmlspecialchars($movieID, ENT_QUOTES, 'UTF-8') . "&showTimeID=" . htmlspecialchars($showtime['showTimeID'], ENT_QUOTES, 'UTF-8') . "' class='showtime-button btn-primary'>" . htmlspecialchars($formattedTime, ENT_QUOTES, 'UTF-8') . "</a>";
            }

            echo "</div>";
            
            echo "</div>";
            echo "</a>";
        }
        echo '</div>';
    }
}
?>

<div class="movie-calendar">
    <?php displayMovieCalendar($db, $selectedDate); ?>
</div>














<!-- ABOUT CINEMA -->




<?php
try {
    $query = $db->prepare("SELECT 
                        Cinema.name, 
                        Cinema.description, 
                        Cinema.street, 
                        Cinema.postalCode, 
                        PostalCode.city 
                       FROM Cinema 
                       INNER JOIN PostalCode ON Cinema.postalCode = PostalCode.postalCode 
                       LIMIT 1");
$query->execute();
$cinema = $query->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching cinema details: " . $e->getMessage();
}
?>


<section class="cinema-section">
    <h2>About <?php echo htmlspecialchars($cinema['name']); ?></h2>

    <div class="cinema-description">
        <div class="cinema-slogan">
            <p>See the magic, <br> feel the story</p>
        </div>
        <div class="cinema-info">
            <p><?php echo nl2br(htmlspecialchars($cinema['description'])); ?></p>
        </div>
    </div>
    </section>


    <div class="parallax-container">
    <div  class="parallax-image-cinema">
    </div>
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
                    <span><?php echo htmlspecialchars($cinema['street']); ?>, <?php echo htmlspecialchars($cinema['postalCode']); ?> <?php echo htmlspecialchars($cinema['city']); ?></span>
                </div>
                <p>After entering Shopping center Broen, head to the main atrium. Take the escalator to the second floor, walk past the food court, and you'll find our cinema entrance.</p>
            </div>
        </div>
    </section>
</section>


    <?php 
// Fetch Cinema Opening Hours from the view
$query = $db->prepare("
    SELECT dayOfWeek, openingTime, closingTime 
    FROM cinema_opening_hours
    WHERE cinemaID = 1
");
$query->execute();
$openingHours = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
  <div class="opening-hours-container">
    <div class="opening-hours">
      <div class="opening-hours-text">
        <h2>Opening Hours</h2>
        <?php if (!empty($openingHours)): ?>
          <?php foreach ($openingHours as $hour): ?>
            <div class="day">
              <span><?php echo htmlspecialchars($hour['dayOfWeek']); ?></span>
              <span>
                <?php 
                  echo htmlspecialchars(date("H:i", strtotime($hour['openingTime']))) . 
                  ' - ' . 
                  htmlspecialchars(date("H:i", strtotime($hour['closingTime']))); 
                ?>
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



