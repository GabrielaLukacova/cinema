<?php 
require_once "../../../includes/connection.php"; 

// Handle form submission for adding new showtime
if (isset($_POST['submit'])) {
    try {
        // Sanitize and validate inputs
        $movieID = $_POST['movieID'] ?? null;
        $date = $_POST['date'] ?? null;
        $time = $_POST['time'] ?? null;
        $room = $_POST['room'] ?? null;
        $price = $_POST['price'] ?? null;

        // Validate required fields
        if (!$movieID || !$date || !$time || !$room) {
            throw new Exception("Missing required fields: movieID, date, time, or room.");
        }

        // Handle price default if not provided
        $price = is_numeric($price) ? $price : 100; // Default price to 100 if empty or invalid

        // Insert showtime data into the database
        $insertQuery = $db->prepare("
            INSERT INTO ShowTime (movieID, date, time, room, price) 
            VALUES (:movieID, :date, :time, :room, :price)
        ");
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

            // Insert 10 rows (A to J) with 12 seats each for every ShowTime
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

            // Redirect back to the movie_calendar_view.php page with success status
            header("Location: ../views/movie_calendar_view.php?status=added");
            exit;
        } else {
            throw new Exception("Error adding showtime: " . implode(", ", $insertQuery->errorInfo()));
        }
    } catch (Exception $e) {
        // Redirect back with error status
        $errorMessage = "Error: " . $e->getMessage();
        header("Location: ../views/movie_calendar_view.php?status=error&message=" . urlencode($errorMessage));
        exit;
    }
}
?>
