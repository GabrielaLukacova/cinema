<?php
// Start the session
session_start();

// Check if the user is logged in (optional check)
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to make a reservation.");
}

// Include database connection (modify path as necessary)
require_once "../../includes/connection.php";

// Check if form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_seats']) && isset($_POST['showTimeID'])) {
    // Get the data sent from the form
    $selectedSeats = json_decode($_POST['selected_seats']);
    $showTimeID = (int)$_POST['showTimeID'];

    // Prepare the email data
    $seatsList = implode(", ", $selectedSeats);  // List the selected seats
    $totalPrice = count($selectedSeats) * 100; 
    
    
    $adminEmail = "gabluk01@easv365.dk";  
    $subject = "New Booking Confirmation - User ID: " . $_SESSION['user_id'];
    
    // Email message
    $message = "A new booking has been made by User ID: " . $_SESSION['user_id'] . "\n";
    $message .= "Showtime ID: " . $showTimeID . "\n";
    $message .= "Seats Selected: " . $seatsList . "\n";
    $message .= "Total Price: DKK " . $totalPrice . "\n";

    // Email headers
    $headers = "From: no-reply@yourcinema.com" . "\r\n" .
               "Reply-To: no-reply@yourcinema.com" . "\r\n" .
               "Content-Type: text/plain; charset=UTF-8" . "\r\n";

    // Send the email
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