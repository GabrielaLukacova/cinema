<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set the current page for the sidebar highlight
$currentPage = basename($_SERVER['PHP_SELF']);

require_once "../actions/ticket_logic.php";
require_once '../templates/user_sidebar.php';
require_once '../../navbar_footer/cinema_navbar.php';



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My tickets</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="tickets-container">
        <?php if (!empty($tickets)): ?>
            <?php foreach ($tickets as $ticket): ?>
                <div class="ticket-card">
                    <img src="../../includes/media/movies/<?= htmlspecialchars($ticket['movieImage'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($ticket['movieTitle'], ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="ticket-info">
                        <h3><?= htmlspecialchars($ticket['movieTitle'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p><strong>Date:</strong> <?= htmlspecialchars($ticket['showDate'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>Time:</strong> <?= htmlspecialchars($ticket['showTime'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>Room:</strong> <?= htmlspecialchars($ticket['roomNumber'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>Seats:</strong> <?= htmlspecialchars($ticket['seatDetails'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="ticket-total">Total Price: DKK<?= number_format((float) $ticket['ticketPrice'], 2); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-tickets">
                <p>You have not booked any tickets yet.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

