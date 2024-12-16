<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../loginPDO/views/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

require_once "../../includes/connection.php";

if (empty($_SESSION['selected_seats']) || empty($_SESSION['showTimeID'])) {
    die("Error: Missing booking details. Please go back and try again.");
}

$selectedSeats = $_SESSION['selected_seats'][$_SESSION['showTimeID']] ?? [];
$showTimeID = $_SESSION['showTimeID'];

if (empty($selectedSeats)) {
    die("Error: No seats selected for this showtime.");
}

try {
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

    $totalPrice = count($selectedSeats) * $showTime['price'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $db->beginTransaction();

        $insertBooking = $db->prepare("
            INSERT INTO Booking (userID, showTimeID)
            VALUES (:userID, :showTimeID)
        ");
        $insertBooking->execute([
            ':userID' => $_SESSION['user_id'],
            ':showTimeID' => $showTimeID,
        ]);

        $bookingID = $db->lastInsertId();

        $updateSeat = $db->prepare("
            UPDATE Seat
            SET isBooked = 1
            WHERE seatID = :seatID AND showTimeID = :showTimeID
        ");

        $insertReserve = $db->prepare("
            INSERT INTO Reserves (bookingID, seatID, bookingDate, bookingTime)
            VALUES (:bookingID, :seatID, NOW(), NOW())
        ");

        foreach ($selectedSeats as $seatID) {
            $updateSeat->execute([':seatID' => $seatID, ':showTimeID' => $showTimeID]);
            $insertReserve->execute([':bookingID' => $bookingID, ':seatID' => $seatID]);
        }

        $db->commit();
        unset($_SESSION['selected_seats'][$showTimeID]);

        // header("Location: tickets.php");
        // exit();
    }
} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

