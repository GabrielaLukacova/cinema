<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../actions/ticket_logic.php";
require_once '../templates/user_sidebar.php';
require_once '../../navbar_footer/cinema_navbar.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .tickets-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin: 20px;
        }
        .ticket-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .ticket-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .ticket-info {
            padding: 15px;
        }
        .ticket-info h3 {
            margin: 0;
            font-size: 1.5rem;
            color: #333;
        }
        .ticket-info p {
            margin: 5px 0;
            color: #666;
        }
        .ticket-info .ticket-total {
            font-weight: bold;
            color: #000;
            margin-top: 10px;
        }
        .no-tickets {
            text-align: center;
            margin: 50px 0;
            font-size: 1.2rem;
            color: #555;
        }
    </style>
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
                        <p class="ticket-total">Total Price: DKK<?= number_format((float)$ticket['ticketPrice'], 2); ?></p>
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
