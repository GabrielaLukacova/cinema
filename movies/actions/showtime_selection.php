<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../includes/connection.php";

// Validate and retrieve `movieID`
if (isset($_GET['movieID']) && is_numeric($_GET['movieID'])) {
    $movieID = (int)$_GET['movieID']; // Cast to integer for security
} else {
    die("Error: Movie ID not set or invalid.");
}

// Fetch showtimes for the given `movieID`
$query = $db->prepare("
    SELECT showTimeID, date, time
    FROM ShowTime
    WHERE movieID = :movieID
    ORDER BY date ASC, time ASC
");
$query->execute([':movieID' => $movieID]);
$showtimes = $query->fetchAll(PDO::FETCH_ASSOC);
?>