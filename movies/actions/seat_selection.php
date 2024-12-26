<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$showTimeID = $_SESSION['showTimeID'];

require_once "../../includes/connection.php";
require_once "../classes/seat.php";
require_once "../views/seat_map.php";

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    $redirectUrl = "../../movies/views/seat_map.php?showTimeID=" . urlencode($_GET['showTimeID']);
    header("Location: ../../loginPDO/views/login.php?redirect=" . urlencode($redirectUrl));
    exit();
}

// Validate and retrieve `showTimeID`
if (!isset($_GET['showTimeID']) || !is_numeric($_GET['showTimeID'])) {
    die("Error: ShowTimeID not set or invalid.");
}

$showTimeID = (int)$_GET['showTimeID'];
$_SESSION['showTimeID'] = $showTimeID;

// Ensure session arrays are initialized
if (!isset($_SESSION['selected_seats'])) {
    $_SESSION['selected_seats'] = [];
}
if (!isset($_SESSION['selected_seats'][$showTimeID])) {
    $_SESSION['selected_seats'][$showTimeID] = [];
}

// Fetch seat data for the given `showTimeID`
$stmt = $db->prepare("
    SELECT s.seatID, s.seatNumber, s.seatRow, s.isBooked, st.price AS seatPrice
    FROM Seat s
    JOIN ShowTime st ON s.showTimeID = st.showTimeID
    WHERE s.showTimeID = :showTimeID
    ORDER BY s.seatRow, s.seatNumber
");
$stmt->execute([':showTimeID' => $showTimeID]);
$seatsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($seatsData)) {
    die("No seats found for this showtime.");
}

// Convert seat data into objects
$seats = array_map(fn($seatData) => new Seat(
    $seatData['seatID'],
    $seatData['seatNumber'],
    $seatData['seatRow'],
    (bool)$seatData['isBooked'],
    $showTimeID,
    (float)$seatData['seatPrice']
), $seatsData);

// Initialize error message
$error = "";

// Handle seat selection form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_seat'])) {
    $seatID = (int)$_POST['toggle_seat'];

    // Check if the seatID is valid
    if (in_array($seatID, array_column($seatsData, 'seatID'))) {
        if (in_array($seatID, $_SESSION['selected_seats'][$showTimeID])) {
            // Deselect seat
            $_SESSION['selected_seats'][$showTimeID] = array_values(
                array_diff($_SESSION['selected_seats'][$showTimeID], [$seatID])
            );
        } elseif (count($_SESSION['selected_seats'][$showTimeID]) < 5) {
            // Select seat if under limit
            $_SESSION['selected_seats'][$showTimeID][] = $seatID;
        } else {
            $error = "You can only select up to 5 seats.";
        }
    }
}

// Recalculate total price and prepare selected seat details
$totalPrice = 0;
$selectedSeatDetails = [];
foreach ($_SESSION['selected_seats'][$showTimeID] as $seatID) {
    foreach ($seats as $seat) {
        if ($seat->id === $seatID) {
            $totalPrice += $seat->price;
            $selectedSeatDetails[] = $seat;
            break;
        }
    }
}



// error_log('Selected Seats in seat_selection.php: ' . print_r($_SESSION['selected_seats'], true));
// header("Location: ../views/ticket_confirmation.php");
// exit();



$selectedSeats = $_SESSION['selected_seats'][$showTimeID];

// Handle booking creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['continue_booking'])) {
    if (!empty($_SESSION['selected_seats'][$showTimeID])) {
        try {
            $db->beginTransaction();

            // Insert booking into the Booking table
            $insertBookingQuery = $db->prepare("
                INSERT INTO Booking (userID, showTimeID) VALUES (:userID, :showTimeID)
            ");
            $insertBookingQuery->execute([
                ':userID' => $_SESSION['user_id'],
                ':showTimeID' => $showTimeID
            ]);

            $bookingID = $db->lastInsertId();

            // Mark selected seats as booked
            $updateSeatQuery = $db->prepare("
                UPDATE Seat SET isBooked = 1 WHERE seatID = :seatID
            ");
            foreach ($_SESSION['selected_seats'][$showTimeID] as $seatID) {
                $updateSeatQuery->execute([':seatID' => $seatID]);
            }

            $db->commit();

            // Clear selected seats after booking
            unset($_SESSION['selected_seats'][$showTimeID]);

            // Redirect to confirmation page
            header("Location: ../views/ticket_confirmation.php?bookingID=$bookingID");
            exit();
        } catch (Exception $e) {
            $db->rollBack();
            $error = "Error processing your booking. Please try again later.";
            error_log($e->getMessage());
        }
    } else {
        $error = "Please select at least one seat before continuing.";
    }
}

if (!isset($_SESSION['selected_seats'][$showTimeID])) {
    $_SESSION['selected_seats'][$showTimeID] = [];
}
error_log(print_r($_SESSION['selected_seats'][$showTimeID], true));


// if (!empty($selectedSeats)) {
//     $_SESSION['selected_seats'][$showTimeID] = $selectedSeats;
//     header("Location: ../views/ticket_confirmation.php");
//     exit();
// } else {
//     $error = "Please select at least one seat before continuing.";
// }

?>
<!-- <form method="POST" action="ticket_confirmation.php">
    <input type="hidden" name="selected_seats" value="<?= htmlspecialchars(json_encode($selectedSeats), ENT_QUOTES, 'UTF-8') ?>">
    <button type="submit" class="continue-btn" <?= empty($selectedSeats) ? 'disabled' : '' ?>>Continue</button>
</form> -->