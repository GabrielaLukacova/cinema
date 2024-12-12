<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../includes/connection.php";
require_once "../classes/seat.php";

// Validate and retrieve `showTimeID`
$showTimeID = isset($_GET['showTimeID']) && is_numeric($_GET['showTimeID']) 
    ? (int)$_GET['showTimeID'] 
    : die("Error: ShowTimeID not set or invalid.");

// Fetch seat data for the given `showTimeID`
$stmt = $db->prepare("
    SELECT seatID, seatNumber, seatRow, isBooked 
    FROM Seat 
    WHERE showTimeID = :showTimeID
");
$stmt->execute([':showTimeID' => $showTimeID]);
$seatsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Map seat data to Seat objects
$seats = array_map(fn($seatData) => new Seat(
    $seatData['seatID'], 
    $seatData['seatNumber'], 
    $seatData['seatRow'], 
    $seatData['isBooked'], 
    $showTimeID
), $seatsData);

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
    <h1>Seat Selection</h1>
    <form method="post" action="seat_booking.php">
        <div class="seat-map">
            <?php foreach ($seats as $seat): ?>
                <?= $seat->renderSeat(in_array($seat->id, $_SESSION['selected_seats'][$showTimeID] ?? [])); ?>
            <?php endforeach; ?>
        </div>
        <button type="submit">Book Selected Seats</button>
    </form>
</body>
</html>

