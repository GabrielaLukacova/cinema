<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once "../../includes/connection.php";

// $showTimeID = $_SESSION['showTimeID'];
// $userID = $_SESSION['user_id'];

// Validate user login
if (empty($_SESSION['user_id'])) {
    die("Error: You must be logged in to view your tickets.");
}

$userID = $_SESSION['user_id'];

try {
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

    // Deduplicate tickets by inferred unique showTimeID
    $uniqueTickets = [];
    foreach ($tickets as $ticket) {
        // Create a unique key based on showDate and showTime
        $key = $ticket['showDate'] . '_' . $ticket['showTime'];
        if (!isset($uniqueTickets[$key])) {
            $uniqueTickets[$key] = $ticket;
        } else {
            // Merge seat details for the same showtime
            $uniqueTickets[$key]['seatDetails'] .= ', ' . $ticket['seatDetails'];
        }
    }
    $tickets = array_values($uniqueTickets); // Re-index the array

    // Ensure $tickets is an array, even if no tickets are found
    if (!$tickets) {
        $tickets = [];
    }
} catch (Exception $e) {
    die("Error: Unable to retrieve tickets. " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
?>


