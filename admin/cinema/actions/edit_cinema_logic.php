<?php
require_once "../../../includes/connection.php";

// Fetch cinemaID from URL
$cinemaID = $_GET['cinemaID'] ?? null;

if (!$cinemaID || !is_numeric($cinemaID)) {
    die("<div class='alert alert-danger'>Invalid Cinema ID.</div>");
}

try {
    // Fetch cinema details
    $query = $db->prepare("SELECT * FROM Cinema WHERE cinemaID = :cinemaID");
    $query->execute([':cinemaID' => $cinemaID]);
    $cinema = $query->fetch(PDO::FETCH_ASSOC);

    if (!$cinema) {
        die("<div class='alert alert-danger'>Cinema not found.</div>");
    }

    // Fetch existing opening hours
    $hoursQuery = $db->prepare("
        SELECT * 
        FROM OpeningHours 
        WHERE cinemaID = :cinemaID 
        ORDER BY FIELD(dayOfWeek, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
    ");
    $hoursQuery->execute([':cinemaID' => $cinemaID]);
    $openingHours = $hoursQuery->fetchAll(PDO::FETCH_ASSOC);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize and validate input
        $name = trim($_POST['name']);
        $phoneNumber = trim($_POST['phoneNumber']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? trim($_POST['email']) : null;
        $street = trim($_POST['street']);
        $postalCode = trim($_POST['postalCode']);
        $description = trim($_POST['description']);

        if (!$email) {
            throw new Exception("Invalid email address.");
        }

        // Update Cinema details
        $updateQuery = $db->prepare("
            UPDATE Cinema 
            SET name = :name, phoneNumber = :phoneNumber, email = :email, 
                street = :street, postalCode = :postalCode, description = :description
            WHERE cinemaID = :cinemaID
        ");
        $updateQuery->execute([
            ':name' => $name,
            ':phoneNumber' => $phoneNumber,
            ':email' => $email,
            ':street' => $street,
            ':postalCode' => $postalCode,
            ':description' => $description,
            ':cinemaID' => $cinemaID
        ]);

        // Update Opening Hours
        foreach ($_POST['openingTime'] as $day => $openingTime) {
            $closingTime = $_POST['closingTime'][$day];
            $updateHoursQuery = $db->prepare("
                UPDATE OpeningHours
                SET openingTime = :openingTime, closingTime = :closingTime
                WHERE cinemaID = :cinemaID AND dayOfWeek = :dayOfWeek
            ");
            $updateHoursQuery->execute([
                ':openingTime' => $openingTime,
                ':closingTime' => $closingTime,
                ':cinemaID' => $cinemaID,
                ':dayOfWeek' => $day
            ]);
        }

        // Redirect back to cinema page
        header("Location: cinema.php?status=updated");
        exit;
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    die("<div class='alert alert-danger'>An error occurred: " . htmlspecialchars($e->getMessage()) . "</div>");
}
?>