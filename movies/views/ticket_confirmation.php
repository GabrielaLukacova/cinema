<?php
require_once "../actions/ticket_confirmation_logic.php"; 
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
            <p>You have booked your tickets. Please pay for the tickets before the movie at the cinema.</p>
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

        <div class="confirmation-footer">
            <a href="../../user_profile/views/tickets.php">View My Tickets</a>
        </div>
    </div>

    <?php require_once "../../navbar_footer/cinema_footer.php"; ?>
</body>
</html>