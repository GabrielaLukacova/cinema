<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session only if it hasn't started yet.
}require_once '../../includes/connection.php';
require_once '../classes/user.php';
include '../../navbar_footer/cinema_navbar.php';
include '../templates/user_sidebar.php';

$userID = 1; // Replace with session or login system to get the actual user ID

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $street = $_POST['street'];
    $postalCode = $_POST['postalCode'];

    // Handle Profile Picture Upload
    $profilePicture = null;
    if (isset($_FILES['userPicture']) && $_FILES['userPicture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/profile_pictures/';
        $uploadFile = $uploadDir . basename($_FILES['userPicture']['name']);
        if (move_uploaded_file($_FILES['userPicture']['tmp_name'], $uploadFile)) {
            $profilePicture = $uploadFile; // Save the file path
        } else {
            echo "Error uploading the profile picture.";
        }
    }

    // Update the User table with the new data
    $query = $db->prepare("
        UPDATE User 
        SET 
            firstName = :firstName,
            lastName = :lastName,
            email = :email,
            phoneNumber = :phoneNumber,
            street = :street,
            postalCode = :postalCode
            " . ($profilePicture ? ", profilePicture = :profilePicture" : "") . " 
        WHERE userID = :userID
    ");
    
    // Bind the parameters for the update query
    $params = [
        ':firstName' => $firstName,
        ':lastName' => $lastName,
        ':email' => $email,
        ':phoneNumber' => $phoneNumber,
        ':street' => $street,
        ':postalCode' => $postalCode,
        ':userID' => $userID
    ];

    if ($profilePicture) {
        $params[':profilePicture'] = $profilePicture; // Add the profile picture path if uploaded
    }

    $query->execute($params);

    // Make sure to call header before any output
    header("Location: user_data.php");
    exit; // Ensure no further code is executed after redirection
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
    <title>Edit profile</title>
</head>

<div class="user-account-main">
    <div class="user-account-data">
        <div class="user-account-form-container">
            <div class="edit-profile-container">
                <form action="user_data.php" method="POST" enctype="multipart/form-data" class="edit-profile-form">
                    <h2>Edit My Profile</h2>

                    <!-- First Name -->
                    <div class="form-group">
                        <label for="firstName">First Name:</label>
                        <input type="text" id="firstName" name="firstName" 
                               value="<?= htmlspecialchars($userData['firstName']); ?>" required>
                    </div>

                    <!-- Last Name -->
                    <div class="form-group">
                        <label for="lastName">Last Name:</label>
                        <input type="text" id="lastName" name="lastName" 
                               value="<?= htmlspecialchars($userData['lastName']); ?>" required>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" 
                               value="<?= htmlspecialchars($userData['email']); ?>" required>
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group">
                        <label for="phoneNumber">Phone Number:</label>
                        <input type="text" id="phoneNumber" name="phoneNumber" 
                               value="<?= htmlspecialchars($userData['phoneNumber']); ?>">
                    </div>

                    <!-- Street -->
                    <div class="form-group">
                        <label for="street">Street:</label>
                        <input type="text" id="street" name="street" 
                               value="<?= htmlspecialchars($userData['street']); ?>">
                    </div>

                    <!-- Postal Code -->
                    <div class="form-group">
                        <label for="postalCode">Postal Code:</label>
                        <input type="text" id="postalCode" name="postalCode" 
                               value="<?= htmlspecialchars($userData['postalCode']); ?>">
                    </div>

                    <!-- Profile Picture -->
                    <div class="form-group">
                        <label for="userPicture">Profile Picture:</label>
                        <input type="file" id="userPicture" name="userPicture" accept="image/jpeg, image/png">
                    </div>

                    <!-- Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Save Changes</button>
                        <a href="user_data.php" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
