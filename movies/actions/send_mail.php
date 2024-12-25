<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to make a reservation.");
}

// Include database connection (if needed)
require_once "../../includes/connection.php";

// Check if POST data exists (seats and showtime)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_seats']) && isset($_POST['showTimeID'])) {
    // Decode selected seats from the JSON data passed in POST
    $selectedSeats = json_decode($_POST['selected_seats']);
    $showTimeID = (int)$_POST['showTimeID'];

    // Prepare data for the email
    $seatsList = implode(", ", $selectedSeats);
    $totalPrice = count($selectedSeats) * 100;

    // Define the admin email and subject
    $adminEmail = "gabluk01@easv365.dk";  
    $subject = "New Booking Confirmation - User ID: " . $_SESSION['user_id'];

    // Create the email message
    $message = "A new booking has been made by User ID: " . $_SESSION['user_id'] . "\n";
    $message .= "Showtime ID: " . $showTimeID . "\n";
    $message .= "Seats Selected: " . $seatsList . "\n";
    $message .= "Total Price: DKK " . $totalPrice . "\n";

    // Email headers
    $headers = "From: no-reply@yourcinema.com" . "\r\n" .
               "Reply-To: no-reply@yourcinema.com" . "\r\n" .
               "Content-Type: text/plain; charset=UTF-8" . "\r\n";

    // Send the email using the built-in mail function
    if (mail($adminEmail, $subject, $message, $headers)) {
        // Redirect to ticket confirmation page after email is sent
        header('Location: ../views/ticket_confirmation.php');
        exit();
    } else {
        echo "There was an error sending the email.";
    }
} else {
    echo "No seat data received.";
}
?>