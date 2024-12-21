<?php
require_once "../actions/seat_selection.php";
// require_once '../../navbar_footer/cinema_navbar.php';

// Generate a CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
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
                    <form method="POST" action="">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                        <div class="seat-map">
                            <?php foreach ($seats as $seat): ?>
                                <button 
                                    type="submit" 
                                    name="toggle_seat" 
                                    value="<?= htmlspecialchars($seat->id, ENT_QUOTES, 'UTF-8') ?>"
                                    class="seat <?= $seat->isBooked ? 'booked' : (in_array($seat->id, $selectedSeats) ? 'selected' : 'available') ?>"
                                    <?= $seat->isBooked ? 'disabled' : '' ?>>
                                    <?= htmlspecialchars($seat->row . $seat->number, ENT_QUOTES, 'UTF-8') ?>
                                </button>
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
                                <li>Seat <?= htmlspecialchars($seat->row . $seat->number, ENT_QUOTES, 'UTF-8') ?> - DKK<?= number_format($seat->price, 2) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="total">Total Price: DKK<?= number_format($totalPrice, 2) ?></div>

                        <!-- Proceed to confirmation -->
                        <form method="POST" action="../actions/confirm_seat_booking.php">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="selected_seats" value="<?= htmlspecialchars(json_encode($selectedSeats), ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="showTimeID" value="<?= htmlspecialchars($showTimeID, ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit" class="continue-btn" <?= empty($selectedSeats) ? 'disabled' : '' ?>>Continue</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="no-seats-message">
                <h2>No seats available for this showtime.</h2>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
