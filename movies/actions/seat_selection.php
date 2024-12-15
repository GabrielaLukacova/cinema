<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_seats'])) {
    $_SESSION['selected_seats'] = $_POST['selected_seats'];
    header("Location: ../login/login.php?redirect=ticket_confirmation.php");
    exit();
}

require_once "../../includes/connection.php";
require_once "../classes/seat.php";

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

// Debugging: Ensure `showTimeID` is correct and check `seatsData`
if (empty($seatsData)) {
    die("No seats found for this showtime. Debug: showTimeID = " . htmlspecialchars($showTimeID));
}

// Convert `seatsData` into an array of Seat objects
$seats = array_map(fn($seatData) => new Seat(
    $seatData['seatID'],
    $seatData['seatNumber'],
    $seatData['seatRow'],
    $seatData['isBooked'],
    $showTimeID,
    $seatData['seatPrice']
), $seatsData);

// Initialize session data for selected seats by showtime
if (!isset($_SESSION['selected_seats'])) {
    $_SESSION['selected_seats'] = [];
}

// Ensure `$_SESSION['selected_seats'][$showTimeID]` is always an array
if (!isset($_SESSION['selected_seats'][$showTimeID]) || !is_array($_SESSION['selected_seats'][$showTimeID])) {
    $_SESSION['selected_seats'][$showTimeID] = [];
}

// Handle seat selection form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_seat']) && is_numeric($_POST['toggle_seat'])) {
    $seatID = (int)$_POST['toggle_seat'];

    // Ensure seatID is valid before processing
    if (in_array($seatID, array_column($seatsData, 'seatID'))) {
        // Check if the seat is already selected
        if (in_array($seatID, $_SESSION['selected_seats'][$showTimeID])) {
            // Remove the seat
            $_SESSION['selected_seats'][$showTimeID] = array_diff($_SESSION['selected_seats'][$showTimeID], [$seatID]);
        } elseif (count($_SESSION['selected_seats'][$showTimeID]) < 5) {
            // Add the seat if within the limit
            $_SESSION['selected_seats'][$showTimeID][] = $seatID;
        } else {
            // Set an error message for exceeding the limit
            $error = "You can only select up to 5 seats for this showtime.";
        }
    }
}

// Calculate the total price for the current showtime
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

