<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../includes/connection.php";

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

    // Group showtimes by movie ID
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

    // Calendar navigation buttons
    echo '<div class="calendar-buttons">';
    for ($i = 0; $i < 7; $i++) {
        $date = clone $today;
        $date->modify("+$i days");
        $formattedDate = $date->format('Y-m-d');
        $dayName = ($i === 0) ? "Today" : ($i === 1 ? "Tomorrow" : $date->format('l, j.n.'));
    
        $buttonClass = ($formattedDate === $selectedDate) ? 'calendar-day btn-primary selected' : 'calendar-day btn-primary';
        echo "<a href='?date=$formattedDate#movie-calendar' class='$buttonClass'>" . htmlspecialchars($dayName, ENT_QUOTES, 'UTF-8') . "</a>";
    }
    echo '</div>';

    // Fetch movies for the selected date
    $movies = getMoviesForDate($db, $selectedDate);

    if (empty($movies)) {
        echo "<p class='no-movies'>No movies scheduled for this day.</p>";
    } else {
        echo '<div class="movie-calendar-container">';
        foreach ($movies as $movieID => $movie) {
            echo "<div class='movie-calendar-item'>";
            echo "<div class='movie-calendar-info-container'>";
            echo "<img class='movie-calendar-image' src='../../includes/media/movies/" . htmlspecialchars($movie['imagePath'], ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8') . "' />";

            // Movie details
            echo "<div class='movie-calendar-info'>";
            echo "<h4 class='movie-calendar-title'>" . htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8') . "</h4>";
            echo "<p class='movie-calendar-details'>";
            echo htmlspecialchars($movie['genre'], ENT_QUOTES, 'UTF-8') . " | " . htmlspecialchars($movie['runtime'], ENT_QUOTES, 'UTF-8') . " min | " . htmlspecialchars($movie['ageRating'], ENT_QUOTES, 'UTF-8') . "+";

            // Language flag
            $flagPath = "../../includes/media/flags/" . strtolower($movie['language']) . "_flag";
            $flagExt = file_exists($flagPath . ".jpg") ? "jpg" : (file_exists($flagPath . ".png") ? "png" : null);
            if ($flagExt) {
                echo " <img src='" . htmlspecialchars("$flagPath.$flagExt", ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($movie['language'], ENT_QUOTES, 'UTF-8') . " Flag' width='20' height='15'>";
            }
            echo "</p>";
            echo "</div>";

            // Showtimes buttons
            echo "<div class='movie-calendar-showtimes-container'>";
            foreach ($movie['showtimes'] as $showtime) {
                $formattedTime = date('H:i', strtotime($showtime['time']));
                echo "<a href='../../movies/views/seat_map.php?movieID=" . htmlspecialchars($movieID, ENT_QUOTES, 'UTF-8') . "&showTimeID=" . htmlspecialchars($showtime['showTimeID'], ENT_QUOTES, 'UTF-8') . "' class='showtime-button btn-primary'>" . htmlspecialchars($formattedTime, ENT_QUOTES, 'UTF-8') . "</a>";
            }
            echo "</div>";

            echo "</div>";
            echo "</div>";
        }
        echo '</div>';
    }
}

// Get selected date from query string or default to today
$selectedDate = isset($_GET['date']) ? $_GET['date'] : (new DateTime())->format('Y-m-d');
?>