<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../includes/connection.php";
require_once "../classes/seat.php";
require_once "../views/seat_map.php";

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) !== 'login.php') {
    $redirectUrl = "../../movies/views/seat_map.php?showTimeID=" . urlencode($_GET['showTimeID']);
    header("Location: ../../loginPDO/views/login.php?redirect=" . urlencode($redirectUrl));
    exit();
}

// Validate and retrieve `showTimeID`
if (!isset($_GET['showTimeID']) || !is_numeric($_GET['showTimeID'])) {
    die("Error: ShowTimeID not set or invalid.");
}

$showTimeID = (int)$_GET['showTimeID'];
$_SESSION['showTimeID'] = $showTimeID;

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


// Initialize error message
$error = "";


// Handle seat selection form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_seat'])) {
    $seatID = (int)$_POST['toggle_seat'];


    // Check if the seatID is valid
    if (in_array($seatID, array_column($seatsData, 'seatID'))) {
        if (in_array($seatID, $_SESSION['selected_seats'][$showTimeID])) {
            // Remove seat
            $_SESSION['selected_seats'][$showTimeID] = array_diff($_SESSION['selected_seats'][$showTimeID], [$seatID]);
        } elseif (count($_SESSION['selected_seats'][$showTimeID]) < 5) {
            // Add seat if under limit
            $_SESSION['selected_seats'][$showTimeID][] = $seatID;
        } else {
            $error = "You can only select up to 5 seats."; // Set error message
        }
    }
}

// Calculate total price and prepare selected seat details
$totalPrice = 0;
$selectedSeatDetails = [];
foreach ($_SESSION['selected_seats'][$showTimeID] as $seatID) {
    foreach ($seats as $seat) {
        if ($seat->id === $seatID) {
            $totalPrice += $seat->price;
            $selectedSeatDetails[] = $seat;
            break;
        }
    }
}

// Pass data for rendering the seat map
$selectedSeats = $_SESSION['selected_seats'][$showTimeID];
