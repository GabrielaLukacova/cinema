<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Email parameters
$to = "gabriela.lukacova002@gmail.com";
$subject = "New Booking Notification";
$message = "A new booking was made.";
$headers = "From: gabriela.lukacova002@gmail.com\r\n";

// Send email
if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email.";
}
?>


<!-- 
<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to make a reservation.");
}

// Include database connection (if needed)
require_once "../../includes/connection.php";

// Debug: Check if session user_id is set
echo "User ID: " . $_SESSION['user_id'] . "<br>";

// Check if POST data exists (seats and showtime)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_seats']) && isset($_POST['showTimeID'])) {
    // Decode selected seats from the JSON data passed in POST
    $selectedSeats = json_decode($_POST['selected_seats']);
    $showTimeID = (int)$_POST['showTimeID'];

    // Debug: Check the POST data
    echo "Selected Seats: " . implode(", ", $selectedSeats) . "<br>";
    echo "Showtime ID: " . $showTimeID . "<br>";

    // Prepare data for the email
    $seatsList = implode(", ", $selectedSeats);
    $totalPrice = count($selectedSeats) * 100;

    // Define the admin email and subject
    $adminEmail = "gabriela.lukacova002@gmail.com";  
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

    // Debug: Check the email message and headers
    echo "Email Message: <br>" . nl2br($message) . "<br>";
    echo "Email Headers: <br>" . nl2br($headers) . "<br>";

    // Send the email using the built-in mail function
    $mailSent = mail($adminEmail, $subject, $message, $headers);
    
    // Debug: Check if email was sent
    if ($mailSent) {
        echo "Email sent successfully!<br>";
        // Redirect to ticket confirmation page after email is sent
        header('Location: ../views/ticket_confirmation.php');
        exit();
    } else {
        echo "Failed to send email.<br>";
    }
} else {
    echo "No seat data received.<br>";
}



// Debug: Check if the correct POST data is available
echo "POST data: <pre>" . print_r($_POST, true) . "</pre>";
?> -->
