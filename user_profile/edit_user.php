<?php
require_once '../includes/connection.php';
require_once 'user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $phoneNumber = htmlspecialchars($_POST['phoneNumber']);
    $email = htmlspecialchars($_POST['email']);
    $street = htmlspecialchars($_POST['street']);
    $postalCode = htmlspecialchars($_POST['postalCode']);

    $query = $db->prepare("
        UPDATE User 
        SET 
            firstName = :firstName, 
            lastName = :lastName,
            phoneNumber = :phoneNumber,
            email = :email,
            street = :street,
            postalCode = :postalCode
        WHERE userID = :userID
    ");
    $query->execute([
        ':firstName' => $firstName,
        ':lastName' => $lastName,
        ':phoneNumber' => $phoneNumber,
        ':email' => $email,
        ':street' => $street,
        ':postalCode' => $postalCode,
        ':userID' => $_POST['userID']
    ]);

    header("Location: profile.php");
    exit;
}
?>


test
