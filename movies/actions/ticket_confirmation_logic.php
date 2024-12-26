<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../includes/connection.php";

// Ensure session arrays are initialized and check data validity
if (!isset($_SESSION['selected_seats']) || !isset($_SESSION['showTimeID']) || !isset($_SESSION['user_id'])) {
    die("Error: Missing booking details. Please go back and select seats.");
}

$showTimeID = $_SESSION['showTimeID'];
$userID = $_SESSION['user_id'];

try {
    // Fetch movie and showtime details
    $query = $db->prepare("
        SELECT st.date, st.time, st.price, st.room, m.title
        FROM ShowTime st
        JOIN Movie m ON st.movieID = m.movieID
        WHERE st.showTimeID = :showTimeID
    ");
    $query->execute([':showTimeID' => $showTimeID]);
    $showTime = $query->fetch(PDO::FETCH_ASSOC);

    if (!$showTime) {
        die("Error: Showtime not found.");
    }

    // Fetch booked seats for the selected showtime and user
    $seatQuery = $db->prepare("
        SELECT s.seatNumber, s.seatRow
        FROM Seat s
        JOIN Booking b ON s.showTimeID = b.showTimeID
        WHERE s.isBooked = 1 AND s.showTimeID = :showTimeID AND b.userID = :userID
        ORDER BY s.seatRow, s.seatNumber ASC
    ");
    $seatQuery->execute([
        ':showTimeID' => $showTimeID,
        ':userID' => $userID,
    ]);
    $seatDetails = $seatQuery->fetchAll(PDO::FETCH_ASSOC);

    if (!$seatDetails) {
        die("Error: Booked seats not found for confirmation.");
    }

    // Calculate total price
    $totalPrice = count($seatDetails) * $showTime['price'];

} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

// Log data for debugging
error_log('Selected Seats: ' . print_r($seatDetails, true));
error_log('ShowTime ID: ' . $_SESSION['showTimeID']);
?>
