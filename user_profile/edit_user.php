<?php
require_once '../includes/connection.php';
require_once 'user.php';


$userID = 1; // Replace with session or login system to get the actual user ID

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = $db->prepare("
        UPDATE User 
        SET 
            firstName = :firstName,
            lastName = :lastName,
            email = :email,
            phoneNumber = :phoneNumber,
            street = :street,
            postalCode = :postalCode
        WHERE userID = :userID
    ");
    $query->execute([
        ':firstName' => $_POST['firstName'],
        ':lastName' => $_POST['lastName'],
        ':email' => $_POST['email'],
        ':phoneNumber' => $_POST['phoneNumber'],
        ':street' => $_POST['street'],
        ':postalCode' => $_POST['postalCode'],
        ':userID' => $userID
    ]);

    header("Location: user_profile.php");
    exit;
}

$query = $db->prepare("SELECT * FROM User WHERE userID = :userID");
$query->execute([':userID' => $userID]);
$userData = $query->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Edit Profile</title>
</head>

    <form action="edit_profile.php" method="POST" class="edit-profile-form">
        <h2>Edit Profile</h2>
        <label for="firstName">First Name:</label>
        <input type="text" name="firstName" value="<?= htmlspecialchars($userData['firstName']); ?>" required>

        <label for="lastName">Last Name:</label>
        <input type="text" name="lastName" value="<?= htmlspecialchars($userData['lastName']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($userData['email']); ?>" required>

        <label for="phoneNumber">Phone Number:</label>
        <input type="text" name="phoneNumber" value="<?= htmlspecialchars($userData['phoneNumber']); ?>">

        <label for="street">Street:</label>
        <input type="text" name="street" value="<?= htmlspecialchars($userData['street']); ?>">

        <label for="postalCode">Postal Code:</label>
        <input type="text" name="postalCode" value="<?= htmlspecialchars($userData['postalCode']); ?>">

        <button type="submit" class="btn-primary">Save Changes</button>
        <a href="user_profile.php" class="btn-secondary">Cancel</a>
    </form>
