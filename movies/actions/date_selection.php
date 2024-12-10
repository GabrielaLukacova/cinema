<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once "../../includes/connection.php";
require_once "../classes/seat.php";

// Validate and retrieve `showTimeID` from the request
$showTimeID = isset($_GET['showTimeID']) && is_numeric($_GET['showTimeID']) 
    ? (int)$_GET['showTimeID'] 
    : die("Error: ShowTimeID not set or invalid.");

// Fetch seat data for the provided `showTimeID`
$stmt = $db->prepare("
    SELECT s.seatID, s.seatNumber, s.seatRow, s.isBooked 
    FROM Seat s
    WHERE s.showTimeID = :showTimeID
");
$stmt->execute([':showTimeID' => $showTimeID]);
$seatsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Populate seats array
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

    foreach ($selectedSeats as $seatID) {
        $db->beginTransaction();
        try {
            // Mark seat as booked
            $updateStmt = $db->prepare("
                UPDATE Seat 
                SET isBooked = 1
                WHERE seatID = :seatID AND isBooked = 0
            ");
            $updateStmt->execute([':seatID' => $seatID]);

            // Insert into Reserves table
            $reserveStmt = $db->prepare("
                INSERT INTO Reserves (bookingID, seatID, bookingDate, bookingTime)
                VALUES (:showTimeID, :seatID, :bookingDate, :bookingTime)
            ");
            $reserveStmt->execute([
                ':showTimeID' => $showTimeID,
                ':seatID' => $seatID,
                ':bookingDate' => $bookingDate,
                ':bookingTime' => $bookingTime
            ]);

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            die("Error booking seat: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
        }
    }

    // Clear session data and redirect
    unset($_SESSION['selected_seats']);
    header("Location: ../viewsticket_confirmation.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Selection</title>
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        .seat {
            width: 50px;
            height: 50px;
            margin: 5px;
            text-align: center;
            display: inline-block;
            border: 1px solid #ccc;
        }

        .seat.available {
            background-color: #28a745;
        }

        .seat.booked {
            background-color: #dc3545;
        }

        .seat input[type="checkbox"] {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Seat Selection</h1>

    <form method="post" action="">
        <div class="seating-map">
            <?php foreach ($seats as $seat): ?>
                <?= $seat->renderSeat(); ?>
            <?php endforeach; ?>
        </div>
        <button type="submit">Book Selected Seats</button>
    </form>
</body>
</html>
