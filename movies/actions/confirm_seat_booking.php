<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../includes/connection.php";

// Validate CSRF token
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Invalid CSRF token.");
}

// Validate session and inputs
$showTimeID = $_POST['showTimeID'] ?? null;
$selectedSeats = json_decode($_POST['selected_seats'] ?? '[]', true);

if (!$showTimeID || empty($selectedSeats)) {
    die("Error: Missing showTimeID or selected seats.");
}

try {
    $db->beginTransaction();

    // Mark seats as booked in the database
    $updateQuery = $db->prepare("
        UPDATE Seat 
        SET isBooked = 1 
        WHERE seatID = :seatID AND showTimeID = :showTimeID
    ");

    foreach ($selectedSeats as $seatID) {
        $updateQuery->execute([':seatID' => $seatID, ':showTimeID' => $showTimeID]);
    }

    $db->commit();

    // Redirect to confirmation page
    header("Location: ../views/ticket_confirmation.php?showTimeID=" . urlencode($showTimeID));
    exit();
} catch (Exception $e) {
    $db->rollBack();
    die("Error booking seats: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
