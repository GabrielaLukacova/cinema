<?php
// Include database connection
require_once "../../includes/connection.php";

// Hardcoded userID for testing
$userID = 7; // Replace with the user ID you want to test

try {
    // SQL query to fetch bookings
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
        GROUP BY b.bookingID, st.date, st.time, st.room, m.title, m.imagePath
        ORDER BY st.date DESC, st.time DESC;
    ");

    // Execute query with the hardcoded userID
    $query->execute([':userID' => $userID]);

    // Fetch results
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    // Display results for debugging
    echo "Testing SQL for User ID: " . $userID . "<br>";
    echo "<pre>";
    print_r($results);
    echo "</pre>";

    if (empty($results)) {
        echo "No bookings found for User ID: " . $userID;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
