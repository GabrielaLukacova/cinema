<?php
require_once "../../includes/connection.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simulate payment and save booking
    $userID = $_SESSION['user_id'] ?? null;
    $showTimeID = $_SESSION['showTimeID'] ?? null;
    $selectedSeats = $_SESSION['selected_seats'] ?? [];

    if ($userID && $showTimeID && !empty($selectedSeats)) {
        $db->beginTransaction();

        try {
            // Insert into Booking table
            $stmt = $db->prepare("INSERT INTO Booking (paymentMethod, userID, showTimeID) VALUES ('CreditCard', :userID, :showTimeID)");
            $stmt->execute([':userID' => $userID, ':showTimeID' => $showTimeID]);
            $bookingID = $db->lastInsertId();

            // Insert into Reserves and mark seats as booked
            foreach ($selectedSeats as $seatID) {
                $stmt = $db->prepare("UPDATE Seat SET isBooked = 1 WHERE seatID = :seatID");
                $stmt->execute([':seatID' => $seatID]);

                $stmt = $db->prepare("INSERT INTO Reserves (bookingID, seatID, bookingDate, bookingTime) VALUES (:bookingID, :seatID, NOW(), NOW())");
                $stmt->execute([':bookingID' => $bookingID, ':seatID' => $seatID]);
            }

            $db->commit();
            unset($_SESSION['selected_seats']);
            unset($_SESSION['total_price']);

            header("Location: ticket_confirmation.php");
            exit();
        } catch (Exception $e) {
            $db->rollBack();
            die("Error: " . htmlspecialchars($e->getMessage()));
        }
    } else {
        die("Error: Missing data for payment.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
</head>
<body>
    <h1>Payment</h1>
    <form method="post" action="">
        <p>Payment Successful. Booking confirmed!</p>
        <button type="submit">Finish</button>
    </form>
</body>
</html>
