<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once "../../includes/connection.php";
require_once "../classes/seat.php";

// Validate and retrieve `showTimeID`
if (isset($_GET['showTimeID']) && is_numeric($_GET['showTimeID'])) {
    $showTimeID = (int)$_GET['showTimeID']; // Cast to integer for security
} else {
    die("Error: ShowTimeID not set or invalid.");
}

// Fetch seat data for the given `showTimeID`
$query = $db->prepare("
    SELECT seatID, seatNumber, seatRow, isBooked
    FROM Seat
    WHERE seatID IN (
        SELECT seatID
        FROM Reserves
        WHERE bookingID IN (
            SELECT bookingID
            FROM Booking
            WHERE showTimeID = :showTimeID
        )
    ) OR isBooked = 0
");
$query->execute([':showTimeID' => $showTimeID]);
$seatsData = $query->fetchAll(PDO::FETCH_ASSOC);



// Convert `seatsData` into an array of Seat objects
$seats = array_map(fn($seatData) => new Seat(
    $seatData['seatID'],
    $seatData['seatNumber'],
    $seatData['seatRow'],
    $seatData['isBooked'],
    $showTimeID
), $seatsData);

// Handle seat booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['selected_seats'])) {
    $selectedSeats = $_POST['selected_seats'];
    $bookingDate = date('Y-m-d');
    $bookingTime = date('H:i:s');
    $userID = $_SESSION['user_id'] ?? null;

    if (!$userID) {
        die("Error: You must be logged in to book seats.");
    }

    // Insert booking record
    $db->beginTransaction();
    try {
        $insertBooking = $db->prepare("
            INSERT INTO Booking (paymentMethod, userID, showTimeID)
            VALUES ('CreditCard', :userID, :showTimeID)
        ");
        $insertBooking->execute([
            ':userID' => $userID,
            ':showTimeID' => $showTimeID
        ]);
        $bookingID = $db->lastInsertId();

        foreach ($selectedSeats as $seatID) {
            // Mark seat as booked
            $updateSeat = $db->prepare("
                UPDATE Seat
                SET isBooked = 1
                WHERE seatID = :seatID AND isBooked = 0
            ");
            $updateSeat->execute([':seatID' => $seatID]);

            // Add reserved seat to Reserves
            $insertReserve = $db->prepare("
                INSERT INTO Reserves (bookingID, seatID, bookingDate, bookingTime)
                VALUES (:bookingID, :seatID, :bookingDate, :bookingTime)
            ");
            $insertReserve->execute([
                ':bookingID' => $bookingID,
                ':seatID' => $seatID,
                ':bookingDate' => $bookingDate,
                ':bookingTime' => $bookingTime
            ]);
        }

        $db->commit();
        header("Location: ../views/confirmation.php");
        exit();
    } catch (Exception $e) {
        $db->rollBack();
        die("Error booking seats: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
}



// Validate and retrieve `showTimeID` from the request
$showTimeID = isset($_GET['showTimeID']) && is_numeric($_GET['showTimeID']) ? (int)$_GET['showTimeID'] : die("Error: ShowTimeID not set or invalid.");

// Fetch seat data for the provided `showTimeID`
$stmt = $db->prepare("
    SELECT seatID, seatNumber, seatRow, isBooked, 10.00 AS price
    FROM Seat
    WHERE seatID NOT IN (
        SELECT seatID FROM Reserves WHERE bookingID IN (
            SELECT bookingID FROM Booking WHERE showTimeID = :showTimeID
        )
    )
    OR isBooked = 0
");
$stmt->execute([':showTimeID' => $showTimeID]);
$seatsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize session data for selected seats
if (!isset($_SESSION['selected_seats'])) {
    $_SESSION['selected_seats'] = [];
}

// Handle seat selection form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_seats'])) {
    $selectedSeats = $_POST['selected_seats'];

    // Ensure the number of seats is within the limit
    if (count($selectedSeats) > 5) {
        $error = "You can only select up to 5 seats.";
    } else {
        // Update session with selected seats
        $_SESSION['selected_seats'] = array_unique(array_merge($_SESSION['selected_seats'], $selectedSeats));
    }

    // Handle seat removal
    if (isset($_POST['remove_seat'])) {
        $seatToRemove = $_POST['remove_seat'];
        $_SESSION['selected_seats'] = array_filter($_SESSION['selected_seats'], fn($seatID) => $seatID != $seatToRemove);
    }

    // Redirect to refresh the seat map
    header("Location: seat_selection.php?showTimeID=$showTimeID");
    exit();
}

// Calculate the total price
$totalPrice = 0;
foreach ($_SESSION['selected_seats'] as $seatID) {
    foreach ($seatsData as $seat) {
        if ($seat['seatID'] == $seatID) {
            $totalPrice += $seat['price'];
        }
    }
}

?>




