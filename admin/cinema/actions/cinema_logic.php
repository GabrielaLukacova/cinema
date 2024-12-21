<?php
require_once "../../../includes/connection.php";

try {
    $query = $db->prepare("
        SELECT 
            c.*, 
            pc.city, 
            oh.dayOfWeek, 
            oh.openingTime, 
            oh.closingTime
        FROM Cinema c
        LEFT JOIN PostalCode pc ON c.postalCode = pc.postalCode
        LEFT JOIN OpeningHours oh ON c.cinemaID = oh.cinemaID
        WHERE c.cinemaID = 1
        ORDER BY FIELD(oh.dayOfWeek, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
    ");
    $query->execute();

    $cinema = [];
    $openingHours = [];

    // Fetch and process data
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        if (empty($cinema)) {
            $cinema = [
                'name' => $row['name'],
                'phoneNumber' => $row['phoneNumber'],
                'email' => $row['email'],
                'street' => $row['street'],
                'postalCode' => $row['postalCode'],
                'city' => $row['city'],
                'description' => $row['description']
            ];
        }
        if (!empty($row['dayOfWeek'])) {
            $openingHours[] = [
                'dayOfWeek' => $row['dayOfWeek'],
                'openingTime' => $row['openingTime'],
                'closingTime' => $row['closingTime']
            ];
        }
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    die("Error fetching cinema details. Please try again later.");
}
?>
