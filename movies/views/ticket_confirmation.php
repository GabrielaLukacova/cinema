<?php
require_once "../../includes/connection.php";
require_once "../../navbar_footer/cinema_navbar.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validate required session data
if (empty($_SESSION['selected_seats']) || empty($_SESSION['showTimeID'])) {
    die("Error: Missing booking details. Please go back and try again.");
}

$selectedSeats = $_SESSION['selected_seats'];
$showTimeID = $_SESSION['showTimeID'];

// Fetch showtime details
$query = $db->prepare("
    SELECT st.date, st.time, st.price, m.title
    FROM ShowTime st
    JOIN Movie m ON st.movieID = m.movieID
    WHERE st.showTimeID = :showTimeID
");
$query->execute([':showTimeID' => $showTimeID]);
$showTime = $query->fetch(PDO::FETCH_ASSOC);

if (!$showTime) {
    die("Error: Showtime not found.");
}

// Calculate total price
$totalPrice = count($selectedSeats) * $showTime['price'];
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
            <p>Please arrive on time and pay in person before the movie.</p>
        </div>

        <div class="confirmation-details">
            <ul>
                <li><strong>Movie:</strong> <?= htmlspecialchars($showTime['title'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Date:</strong> <?= htmlspecialchars($showTime['date'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Time:</strong> <?= htmlspecialchars($showTime['time'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Seats:</strong> <?= implode(", ", array_map(fn($seatID) => "Seat {$seatID}", $selectedSeats)); ?></li>
                <li class="confirmation-total"><strong>Total Price:</strong> DKK<?= number_format($totalPrice, 2); ?></li>
            </ul>
        </div>

        <div class="confirmation-footer">
            <a href="tickets.php">View My Tickets</a>
        </div>
    </div>

    <?php require_once "../../navbar_footer/cinema_footer.php"; ?>
</body>
</html>
