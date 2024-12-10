<?php
require_once "../../includes/connection.php";
require_once "../../navbar_footer/cinema_navbar.php";
require_once "../classes/seat.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validate `showTimeID`
if (!isset($_GET['showTimeID']) || !is_numeric($_GET['showTimeID'])) {
    die("Error: ShowTimeID not set or invalid.");
}
$showTimeID = (int)$_GET['showTimeID'];
$_SESSION['showTimeID'] = $showTimeID;

// Fetch seat data for the given `showTimeID`
$query = $db->prepare("
    SELECT seatID, seatNumber, seatRow, isBooked, st.price AS seatPrice
    FROM Seat s
    CROSS JOIN ShowTime st ON st.showTimeID = :showTimeID
    ORDER BY s.seatRow, s.seatNumber
");
$query->execute([':showTimeID' => $showTimeID]);
$seatsData = $query->fetchAll(PDO::FETCH_ASSOC);

// Initialize or retrieve selected seats from session
if (!isset($_SESSION['selected_seats'])) {
    $_SESSION['selected_seats'] = [];
}

// Handle seat selection form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toggle_seat']) && is_numeric($_POST['toggle_seat'])) {
        $seatID = (int)$_POST['toggle_seat'];

        if (in_array($seatID, $_SESSION['selected_seats'])) {
            $_SESSION['selected_seats'] = array_diff($_SESSION['selected_seats'], [$seatID]);
        } elseif (count($_SESSION['selected_seats']) < 5) {
            $_SESSION['selected_seats'][] = $seatID;
        }
    }
}

// Calculate total price and fetch selected seat details
$totalPrice = 0;
$selectedSeatDetails = [];
foreach ($_SESSION['selected_seats'] as $seatID) {
    foreach ($seatsData as $seat) {
        if ($seat['seatID'] == $seatID) {
            $totalPrice += $seat['seatPrice'];
            $selectedSeatDetails[] = $seat;
            break;
        }
    }
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
        <div class="seat-selection-container">
            <!-- Seat Map -->
            <div class="seat-map-container">
                <div class="screen">Screen</div>
                <form method="post" action="">
                    <div class="seat-map">
                        <?php foreach ($seatsData as $seat): ?>
                            <button 
                                type="submit" 
                                name="toggle_seat" 
                                value="<?= htmlspecialchars($seat['seatID'], ENT_QUOTES, 'UTF-8'); ?>"
                                class="seat <?= $seat['isBooked'] ? 'booked' : (in_array($seat['seatID'], $_SESSION['selected_seats']) ? 'selected' : 'available'); ?>"
                                <?= $seat['isBooked'] ? 'disabled' : ''; ?>
                            >
                                <?= htmlspecialchars($seat['seatRow'] . $seat['seatNumber'], ENT_QUOTES, 'UTF-8'); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </form>
            </div>

            <!-- Overview -->
            <div class="overview-container">
                <div class="overview">
                    <h2>Your Selected Seats</h2>
                    <ul>
                        <?php foreach ($selectedSeatDetails as $seat): ?>
                            <li>Seat <?= htmlspecialchars($seat['seatRow'] . $seat['seatNumber'], ENT_QUOTES, 'UTF-8'); ?> - DKK<?= number_format($seat['seatPrice'], 2); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="total">Total Price: DKK<?= number_format($totalPrice, 2); ?></div>
                    <form method="post" action="ticket_confirmation.php">
                        <input type="hidden" name="selected_seats" value="<?= htmlspecialchars(json_encode($_SESSION['selected_seats']), ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit" class="continue-btn" <?= empty($_SESSION['selected_seats']) ? 'disabled' : ''; ?>>Continue</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
