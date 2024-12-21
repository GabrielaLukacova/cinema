<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../includes/connection.php";
require_once "../../admin/movies/classes/movie.php";
require_once "../classes/seat.php";
require_once "../views/seat_map.php";

// Validate and retrieve `movieID` and `showTimeID`
$movieID = isset($_GET['movieID']) && is_numeric($_GET['movieID']) ? (int)$_GET['movieID'] : null;
$showTimeID = isset($_GET['showTimeID']) && is_numeric($_GET['showTimeID']) ? (int)$_GET['showTimeID'] : null;

if (!$movieID && !$showTimeID) {
    die("Error: Missing or invalid movieID or showTimeID.");
}

// Initialize Movie and fetch movie details
$movieHandler = new Movie($db);

if ($movieID) {
    $movie = $movieHandler->getMovieByID($movieID);
    if (!$movie) {
        die("Error: Movie not found.");
    }

    // Get selected date or default to today
    $selectedDate = isset($_GET['date']) ? $_GET['date'] : (new DateTime())->format('Y-m-d');

    // Fetch available dates for the movie
    $dateQuery = $db->prepare("
        SELECT DISTINCT date 
        FROM ShowTime 
        WHERE movieID = :movieID 
        ORDER BY date ASC
    ");
    $dateQuery->execute([':movieID' => $movieID]);
    $availableDates = $dateQuery->fetchAll(PDO::FETCH_COLUMN);

    // Fetch showtimes for the selected date
    $showtimeQuery = $db->prepare("
        SELECT showTimeID, time 
        FROM ShowTime 
        WHERE movieID = :movieID AND date = :date 
        ORDER BY time ASC
    ");
    $showtimeQuery->execute([':movieID' => $movieID, ':date' => $selectedDate]);
    $showtimes = $showtimeQuery->fetchAll(PDO::FETCH_ASSOC);
}

// Handle seat map functionality for `showTimeID`
if ($showTimeID) {
    $_SESSION['showTimeID'] = $showTimeID;

    // Redirect to login if user is not logged in
    if (!isset($_SESSION['user_id'])) {
        $redirectUrl = "../../movies/views/seat_map.php?showTimeID=" . urlencode($showTimeID);
        header("Location: ../../loginPDO/views/login.php?redirect=" . urlencode($redirectUrl));
        exit();
    }

    $userID = $_SESSION['user_id']; // Retrieve logged-in user's ID

    // Fetch seat data for the given `showTimeID`
    $stmt = $db->prepare("
        SELECT s.seatID, s.seatNumber, s.seatRow, s.isBooked, st.price AS seatPrice
        FROM Seat s
        JOIN ShowTime st ON s.showTimeID = st.showTimeID
        WHERE s.showTimeID = :showTimeID
        ORDER BY s.seatRow, s.seatNumber
    ");
    $stmt->execute([':showTimeID' => $showTimeID]);
    $seatsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($seatsData)) {
        die("No seats found for this showtime.");
    }

    // Convert seat data into objects
    $seats = array_map(fn($seatData) => new Seat(
        $seatData['seatID'],
        $seatData['seatNumber'],
        $seatData['seatRow'],
        (bool)$seatData['isBooked'],
        $showTimeID,
        (float)$seatData['seatPrice']
    ), $seatsData);

    // Initialize selected seats in the session
    if (!isset($_SESSION['selected_seats'][$showTimeID])) {
        $_SESSION['selected_seats'][$showTimeID] = [];
    }
    $selectedSeats = $_SESSION['selected_seats'][$showTimeID];

    // Handle seat selection form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_seat'])) {
        $seatID = (int)$_POST['toggle_seat'];

        // Check if the seatID is valid
        if (in_array($seatID, array_column($seatsData, 'seatID'))) {
            if (in_array($seatID, $selectedSeats)) {
                // Remove seat
                $selectedSeats = array_diff($selectedSeats, [$seatID]);
            } elseif (count($selectedSeats) < 5) {
                // Add seat if under limit
                $selectedSeats[] = $seatID;
            } else {
                $error = "You can only select up to 5 seats.";
            }
            $_SESSION['selected_seats'][$showTimeID] = $selectedSeats; // Update session data
        }
    }

    // Insert booking into the database
    if (!empty($selectedSeats)) {
        $insertBookingQuery = $db->prepare("
            INSERT INTO Booking (userID, showTimeID) VALUES (:userID, :showTimeID)
        ");
        $insertBookingQuery->execute([':userID' => $userID, ':showTimeID' => $showTimeID]);
        $bookingID = $db->lastInsertId();

        // Update seats as booked
        $updateSeatQuery = $db->prepare("
            UPDATE Seat SET isBooked = 1 WHERE seatID = :seatID AND showTimeID = :showTimeID
        ");
        foreach ($selectedSeats as $seatID) {
            $updateSeatQuery->execute([':seatID' => $seatID, ':showTimeID' => $showTimeID]);
        }
    }

    // Calculate total price and prepare selected seat details
    $totalPrice = 0;
    $selectedSeatDetails = [];
    foreach ($selectedSeats as $seatID) {
        foreach ($seats as $seat) {
            if ($seat->id === $seatID) {
                $totalPrice += $seat->price;
                $selectedSeatDetails[] = $seat;
                break;
            }
        }
    }
}
?>
