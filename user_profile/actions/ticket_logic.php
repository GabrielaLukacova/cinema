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

// Debug the user ID
// echo "Logged-in User ID: $userID<br>";

$query = $db->prepare("
    SELECT 
        b.bookingID,
        m.title AS movieTitle,
        m.imagePath AS movieImage,
        st.date AS showDate,
        st.time AS showTime,
        st.room AS roomNumber,
        st.price AS ticketPrice,
        GROUP_CONCAT(CONCAT(s.seatRow, s.seatNumber) ORDER BY s.seatRow, s.seatNumber SEPARATOR ', ') AS seatDetails
    FROM Booking b
    LEFT JOIN ShowTime st ON b.showTimeID = st.showTimeID
    LEFT JOIN Movie m ON st.movieID = m.movieID
    LEFT JOIN Reserves r ON b.bookingID = r.bookingID
    LEFT JOIN Seat s ON r.seatID = s.seatID
    WHERE b.userID = :userID
    GROUP BY b.bookingID, st.date, st.time, st.room, m.title, m.imagePath
    ORDER BY st.date DESC, st.time DESC;
");

try {
    $query->execute([':userID' => $userID]);
    $tickets = $query->fetchAll(PDO::FETCH_ASSOC);

    // Debugging output
    echo "<pre>User ID: $userID\n";
    print_r($tickets);
    echo "</pre>";
} catch (Exception $e) {
    die("Query Error: " . $e->getMessage());
}

// Ensure $tickets is an array, even if no tickets are found
if (!$tickets) {
    $tickets = [];
}
?>
