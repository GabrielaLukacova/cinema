<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header("Location: ../login/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

require_once "../../includes/connection.php";
require_once "../../navbar_footer/cinema_navbar.php";

// Validate required session data
if (empty($_SESSION['selected_seats']) || empty($_SESSION['showTimeID'])) {
    die("Error: Missing booking details. Please go back and try again.");
}

$selectedSeats = $_SESSION['selected_seats'][$_SESSION['showTimeID']] ?? [];
$showTimeID = $_SESSION['showTimeID'];

if (empty($selectedSeats)) {
    die("Error: No seats selected for this showtime.");
}

try {
    // Fetch showtime and movie details
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

    // Fetch seat details for the selected seats
    $placeholders = implode(',', array_fill(0, count($selectedSeats), '?'));
    $seatQuery = $db->prepare("
        SELECT seatRow, seatNumber
        FROM Seat
        WHERE seatID IN ($placeholders)
        AND showTimeID = ?
    ");
    $seatQuery->execute([...$selectedSeats, $showTimeID]);
    $seatDetails = $seatQuery->fetchAll(PDO::FETCH_ASSOC);

    if (!$seatDetails) {
        die("Error: Selected seats not found.");
    }

    // Calculate total price
    $totalPrice = count($selectedSeats) * $showTime['price'];

    // Handle seat booking (mark seats as booked)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $db->beginTransaction();

        try {
            // Insert into Booking table
            $insertBooking = $db->prepare("
                INSERT INTO Booking (userID, showTimeID)
                VALUES (:userID, :showTimeID)
            ");
            $insertBooking->execute([
                ':userID' => $_SESSION['user_id'],
                ':showTimeID' => $showTimeID
            ]);

            $bookingID = $db->lastInsertId();

            // Mark seats as booked and add reservations
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

            // Clear selected seats after successful booking
            unset($_SESSION['selected_seats'][$showTimeID]);

            // Redirect to tickets page
            // header("Location: tickets.php");
            // exit();
        } catch (Exception $e) {
            $db->rollBack();
            die("Error processing booking: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
        }
    }
} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="confirmation-container">
        <div class="confirmation-header">
            <h1>Booking Confirmation</h1>
            <p>Please review your booking details and confirm.</p>
        </div>

        <div class="confirmation-details">
            <ul>
                <li><strong>Movie:</strong> <?= htmlspecialchars($showTime['title'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Date:</strong> <?= htmlspecialchars($showTime['date'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Time:</strong> <?= htmlspecialchars($showTime['time'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Room:</strong> <?= htmlspecialchars($showTime['room'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Seats:</strong> 
                    <?= htmlspecialchars(implode(', ', array_map(
                        fn($seat) => $seat['seatRow'] . $seat['seatNumber'],
                        $seatDetails
                    )), ENT_QUOTES, 'UTF-8'); ?>
                </li>
                <li class="confirmation-total"><strong>Total Price:</strong> DKK<?= number_format($totalPrice, 2); ?></li>
            </ul>
        </div>

        <form method="post">
            <button type="submit" class="btn btn-primary">Confirm Booking</button>
        </form>

        <div class="confirmation-footer">
            <a href="../../user_profile/views/tickets.php">View My Tickets</a>
        </div>
    </div>

    <?php require_once "../../navbar_footer/cinema_footer.php"; ?>
</body>
</html>
