<?php 
require_once "../../includes/connection.php"; 
require_once "../components/admin_navbar.php"; 

// Handle form submission for adding new showtime
if (isset($_POST['submit'])) {
    $movieID = $_POST['movieID'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $room = $_POST['room'];
    $price = $_POST['price'];

    // Insert showtime data into the database
    $insertQuery = $db->prepare("INSERT INTO ShowTime (movieID, date, time, room, price) 
                                   VALUES (:movieID, :date, :time, :room, :price)");
    $executeResult = $insertQuery->execute([
        ':movieID' => $movieID,
        ':date' => $date,
        ':time' => $time,
        ':room' => $room,
        ':price' => $price
    ]);

    if ($executeResult) {
        // Get the newly inserted showTimeID
        $newShowTimeID = $db->lastInsertId();

    //Insert 10 rows (A to J) with 12 seats each for every ShowTime
        $seatInsertQuery = $db->prepare("
            INSERT INTO Seat (seatNumber, seatRow, isBooked, showTimeID)
            SELECT seatNumber, seatRow, FALSE, :newShowTimeID
            FROM (
                SELECT t1.number AS seatNumber, CHAR(64 + t2.seatRowNum) AS seatRow
                FROM 
                    (SELECT 1 AS number UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 
                     UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 
                     UNION ALL SELECT 11 UNION ALL SELECT 12) AS t1
                CROSS JOIN 
                    (SELECT 1 AS seatRowNum UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 
                     UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10) AS t2
            ) seatGrid
        ");
        $seatInsertQuery->execute([':newShowTimeID' => $newShowTimeID]);

        // Redirect back to the movie_calendar.php page with success status
        header("Location: movie_calendar.php?status=added");
        exit;
    } else {
        // Redirect back with error status
        $errorMessage = "Error adding showtime: " . implode(", ", $insertQuery->errorInfo());
        header("Location: movie_calendar.php?status=error&message=" . urlencode($errorMessage));
        exit;
    }
}
?>
