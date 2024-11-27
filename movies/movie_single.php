<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../includes/connection.php";
require_once "../navbar_footer/cinema_navbar.php";


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
    <link rel="stylesheet" href="../css/style.css">
</head>


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












<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Seat Class
class Seat {
    public $id;
    public $number;
    public $row;
    public $isBooked;
    public $showTimeID;

    public function __construct($id, $number, $row, $isBooked, $showTimeID) {
        $this->id = $id;
        $this->number = $number;
        $this->row = $row;
        $this->isBooked = $isBooked;
        $this->showTimeID = $showTimeID;
    }

    public function renderSeat() {
        $status = $this->isBooked ? 'booked' : (isset($_SESSION['selected_seats'][$this->id]) ? 'selected' : 'available');
        return "<div class='seat $status'>
                    <input type='checkbox' name='selected_seats[]' value='{$this->id}' " .
               ($status === 'selected' ? 'checked' : '') . " " . ($this->isBooked ? 'disabled' : '') . ">
                    Seat {$this->number}{$this->row}
                </div>";
    }
}

// Check if `showTimeID` is provided
if (!isset($_GET['showTimeID'])) {
    die("Error: showTimeID not set.");
}
$showTimeID = $_GET['showTimeID'];

// Fetch seats from the database for the given showTimeID
$stmt = $db->prepare("
    SELECT s.seatID, s.seatNumber, s.seatRow, s.isBooked, ? AS showTimeID
    FROM Seat s
");
$stmt->execute([$showTimeID]);
$seatsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Populate seats array
$seats = [];
foreach ($seatsData as $seatData) {
    $seats[] = new Seat($seatData['seatID'], $seatData['seatNumber'], $seatData['seatRow'], $seatData['isBooked'], $seatData['showTimeID']);
}

// Handle form submission to book seats
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_seats'])) {
    $selectedSeats = $_POST['selected_seats'];
    $bookingDate = date('Y-m-d');
    $bookingTime = date('H:i:s');

    foreach ($selectedSeats as $seatID) {
        $stmt = $db->prepare("
            UPDATE Seat 
            SET isBooked = 1
            WHERE seatID = ? AND NOT isBooked
        ");
        $stmt->execute([$seatID]);

        // Insert into Reserves table
        $stmt = $db->prepare("
            INSERT INTO Reserves (bookingID, seatID, bookingDate, bookingTime)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$showTimeID, $seatID, $bookingDate, $bookingTime]);
    }

    
    unset($_SESSION['selected_seats']);
    header("Location: confirmation.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Selection</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .seat {
            width: 50px;
            height: 50px;
            margin: 5px;
            text-align: center;
            display: inline-block;
            border: 1px solid #ccc;
        }

        .seat.available {
            background-color: #28a745;
        }

        .seat.selected {
            background-color: #007bff;
        }

        .seat.booked {
            background-color: #dc3545;
        }

        .seat input[type="checkbox"] {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Seat Selection</h1>

    <form method="post" action="">
        <div class="seating-map">
            <?php
            
            foreach ($seats as $seat) {
                echo $seat->renderSeat();
            }
            ?>
        </div>

        <button type="submit">Book Selected Seats</button>
    </form>







<?php 
require_once "../navbar_footer/cinema_footer.php";
?>
