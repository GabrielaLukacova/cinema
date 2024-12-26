<?php
require_once "../actions/ticket_confirmation_logic.php";
require_once '../../navbar_footer/cinema_navbar.php';
error_log('ShowTimeID: ' . $_SESSION['showTimeID']);
error_log('Selected Seats: ' . print_r($_SESSION['selected_seats'], true));
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
                        array_unique($seatDetails, SORT_REGULAR)
                    )), ENT_QUOTES, 'UTF-8'); ?>
                </li>
                <li class="confirmation-total"><strong>Total Price:</strong> DKK<?= number_format($totalPrice, 2); ?></li>
            </ul>
        </div>

    <div class="confirmation-footer">
        <form method="POST" action="../actions/send_mail.php">
                <button type="submit" class="btn btn-primary" >Send a mail about booking</button>
            </form>

        <a href="../../user_profile/views/tickets.php" class="btn btn-primary">View my tickets</a>
    </div>    
</div>


    <?php require_once "../../navbar_footer/cinema_footer.php"; ?>
</body>
</html>
