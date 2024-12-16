<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Redirect to login if user is not logged in and the page requires it
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header("Location: ../../loginPDO/views/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}
require_once "../actions/seat_selection.php"; 
require_once "../../includes/connection.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Selection</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="seat-overview">
        <?php if (!empty($seats)): ?>
            <div class="seat-selection-container">
                <!-- Seat Map -->
                <div class="seat-map-container">
                    <div class="screen">Screen</div>
                    <form method="post" action="">
                        <div class="seat-map">
                            <?php foreach ($seats as $seat): ?>
                                <?= $seat->renderSeat(in_array($seat->id, $_SESSION['selected_seats'][$showTimeID] ?? [])); ?>
                            <?php endforeach; ?>
                        </div>
                    </form>
                </div>

                <!-- Dynamic Ticket Overview -->
                <div class="overview-container">
                    <div class="overview">
                        <h2>Your Selected Seats</h2>
                        <ul>
                            <?php foreach ($selectedSeatDetails as $seat): ?>
                                <li>Seat <?= htmlspecialchars($seat->row . $seat->number, ENT_QUOTES, 'UTF-8'); ?> - DKK<?= number_format($seat->price, 2); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="total">Total Price: DKK<?= number_format($totalPrice, 2); ?></div>
                        <form method="post" action="ticket_confirmation.php">
                            <input type="hidden" name="selected_seats" value="<?= htmlspecialchars(json_encode($_SESSION['selected_seats'][$showTimeID] ?? []), ENT_QUOTES, 'UTF-8'); ?>">
                            <button type="submit" class="continue-btn" <?= empty($_SESSION['selected_seats'][$showTimeID] ?? []) ? 'disabled' : ''; ?>>Continue</button>
                        </form>

                        <!-- Error Message for Exceeding Seat Limit -->
                        <?php if (!empty($error)): ?>
                            <div class="seat-message">
                                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="no-seats-message">
                <h2>No seats found for this showtime.</h2>
                <p>Please go back and select a different showtime.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>