<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../includes/connection.php";

// Validate user login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../loginPDO/views/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

// Validate session data
if (empty($_SESSION['selected_seats']) || empty($_SESSION['showTimeID'])) {
    die("Error: Missing booking details. Please go back and select seats.");
}

$showTimeID = $_SESSION['showTimeID'];
$selectedSeats = $_SESSION['selected_seats'][$showTimeID] ?? [];

// Validate selected seats
if (empty($selectedSeats)) {
    die("Error: No seats selected.");
}

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

    // Fetch seat details
    $placeholders = implode(',', array_fill(0, count($selectedSeats), '?'));
    $seatQuery = $db->prepare("
        SELECT seatRow, seatNumber
        FROM Seat
        WHERE seatID IN ($placeholders) AND showTimeID = ?
    ");
    $seatQuery->execute([...$selectedSeats, $showTimeID]);
    $seatDetails = $seatQuery->fetchAll(PDO::FETCH_ASSOC);

    if (!$seatDetails) {
        die("Error: Selected seats not found.");
    }

    // Calculate total price
    $totalPrice = count($selectedSeats) * $showTime['price'];

} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

 
