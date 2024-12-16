<?php
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// Include database connection
require_once "../../includes/connection.php";
// Validate user login
if (empty($_SESSION['user_id'])) {
    die("Error: You must be logged in to view your tickets.");
}

$userID = $_SESSION['user_id'];

// Fetch ticket details for the logged-in user
$query = $db->prepare("
    SELECT 
        m.title AS movieTitle,
        m.imagePath AS movieImage,
        st.date AS showDate,
        st.time AS showTime,
        st.room AS roomNumber,
        st.price AS ticketPrice,
        GROUP_CONCAT(CONCAT(s.seatRow, s.seatNumber) ORDER BY s.seatRow, s.seatNumber SEPARATOR ', ') AS seatDetails
    FROM Booking b
    JOIN ShowTime st ON b.showTimeID = st.showTimeID
    JOIN Movie m ON st.movieID = m.movieID
    JOIN Reserves r ON b.bookingID = r.bookingID
    JOIN Seat s ON r.seatID = s.seatID
    WHERE b.userID = :userID
    GROUP BY b.bookingID
    ORDER BY st.date DESC, st.time DESC
");
$query->execute([':userID' => $userID]);

$tickets = $query->fetchAll(PDO::FETCH_ASSOC);

if (!$tickets) {
    $tickets = [];
}
?>
