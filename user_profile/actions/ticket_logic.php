<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once "../../includes/connection.php";

// Validate user login
if (empty($_SESSION['user_id'])) {
    die("Error: You must be logged in to view your tickets.");
}

$userID = $_SESSION['user_id'];

// Create the ticket_info_view if it doesn't exist
try {
    // Check if the view exists first
    $checkView = $db->query("SHOW TABLES LIKE 'ticket_info_view'");
    if ($checkView->rowCount() == 0) {
        // Create the view if it doesn't exist
        $createViewQuery = "
        CREATE VIEW ticket_info_view AS
        SELECT 
            m.title AS movieTitle,
            m.imagePath AS movieImage,
            st.date AS showDate,
            st.time AS showTime,
            st.room AS roomNumber,
            st.price AS ticketPrice,
            GROUP_CONCAT(CONCAT(s.seatRow, s.seatNumber) ORDER BY s.seatRow, s.seatNumber SEPARATOR ', ') AS seatDetails,
            b.userID
        FROM Booking b
        JOIN ShowTime st ON b.showTimeID = st.showTimeID
        JOIN Movie m ON st.movieID = m.movieID
        JOIN Reserves r ON b.bookingID = r.bookingID
        JOIN Seat s ON r.seatID = s.seatID
        GROUP BY b.bookingID;
        ";
        
        $db->exec($createViewQuery);
    }
} catch (PDOException $e) {
    die("Error creating the view: " . $e->getMessage());
}

// Fetch ticket details for the logged-in user
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
    ORDER BY showDate DESC, showTime DESC
");

$query->execute([':userID' => $userID]);

$tickets = $query->fetchAll(PDO::FETCH_ASSOC);

if (!$tickets) {
    $tickets = [];
}
?>


