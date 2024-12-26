<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$to = "gabriela.lukacova002@gmail.com";
$subject = "New booking notification";
$message = "A new booking was made.";
$headers = "From: gabriela.lukacova002@gmail.com\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email.";
}
?>