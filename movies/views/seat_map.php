<?php
// Include seat selection logic
require_once "../actions/seat_selection.php"; 
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

            <!-- dynamic ticket overview -->
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

                                <!-- message when you cross limit of 5 seats -->
                                <?php if (!empty($error)): ?>
                    <div class="seat-message">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

