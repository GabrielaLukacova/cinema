<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../includes/connection.php";

// Validate user login
if (empty($_SESSION['user_id'])) {
    die("Error: You must be logged in to view your tickets.");
}

$userID = $_SESSION['user_id'];

    // Query the SQL view for the logged-in user's tickets
    $query = $db->prepare("
        SELECT 
            movieTitle,
            movieImage,
            showDate,
            showTime,
            roomNumber,
            ticketPrice,
            seatDetails
        FROM ticket_info_view
        WHERE userID = :userID
    ");
    $query->execute([':userID' => $userID]);
    $tickets = $query->fetchAll(PDO::FETCH_ASSOC);

// Ensure $tickets is an array, even if no tickets are found
if (!$tickets) {
    $tickets = [];
}
?>

