<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../includes/connection.php";

// Fetch cinema details
try {
    $cinemaQuery = $db->prepare("
        SELECT 
            Cinema.name, 
            Cinema.description, 
            Cinema.street, 
            Cinema.postalCode, 
            PostalCode.city 
        FROM Cinema 
        INNER JOIN PostalCode ON Cinema.postalCode = PostalCode.postalCode 
        LIMIT 1
    ");
    $cinemaQuery->execute();
    $cinema = $cinemaQuery->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching cinema details: " . $e->getMessage());
    $cinema = null;
}

// Fetch cinema opening hours
try {
    $openingHoursQuery = $db->prepare("
        SELECT dayOfWeek, openingTime, closingTime 
        FROM cinema_opening_hours
        WHERE cinemaID = 1
    ");
    $openingHoursQuery->execute();
    $openingHours = $openingHoursQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching cinema opening hours: " . $e->getMessage());
    $openingHours = [];
}
?>