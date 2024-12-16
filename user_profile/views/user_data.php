<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session only if it hasn't started yet.
}

require_once '../classes/user.php';
require_once '../templates/user_sidebar.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit();
}

$userID = $_SESSION['user_id'];
$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'firstName' => htmlspecialchars(trim($_POST['firstName'] ?? ''), ENT_QUOTES, 'UTF-8'),
        'lastName' => htmlspecialchars(trim($_POST['lastName'] ?? ''), ENT_QUOTES, 'UTF-8'),
        'phoneNumber' => htmlspecialchars(trim($_POST['phoneNumber'] ?? ''), ENT_QUOTES, 'UTF-8'),
        'street' => htmlspecialchars(trim($_POST['street'] ?? ''), ENT_QUOTES, 'UTF-8'),
        'postalCode' => htmlspecialchars(trim($_POST['postalCode'] ?? ''), ENT_QUOTES, 'UTF-8'),
    ];

    $user->updateUserProfile($userID, $data);

    if (!empty($_FILES['userPicture']['name'])) {
        $uploadDir = '../../includes/media/users/';
        $fileName = basename($_FILES['userPicture']['name']);
        $filePath = $uploadDir . $fileName;
        $fileType = mime_content_type($_FILES['userPicture']['tmp_name']);

        if (in_array($fileType, ['image/jpeg', 'image/png'])) {
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($_FILES['userPicture']['tmp_name'], $filePath)) {
                $user->updateUserPicture($userID, htmlspecialchars($filePath, ENT_QUOTES, 'UTF-8'));
            } else {
                die('Failed to upload file.');
            }
        } else {
            die('Invalid file type. Only JPEG and PNG are allowed.');
        }
    }

    header('Location: user_data.php');
    exit();
}

$userData = $user->getUserProfile($userID);

// Check if $userData is valid
if (!$userData || !is_array($userData)) {
    $userData = [
        'firstName' => 'Guest',
        'lastName' => '',
        'phoneNumber' => '',
        'street' => '',
        'postalCode' => '',
        'userPicture' => '../../includes/media/other/user_default.png',
    ];
}

$userData = array_map(fn($value) => htmlspecialchars($value, ENT_QUOTES, 'UTF-8'), $userData);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Edit user data</title>
</head>
<body>

<div class="user-account-main">
    <div class="user-account-data">
        <div class="user-account-form-container">

            <!-- Name and Surname -->
            <div class="form-group">
                <label>Name:</label>
                <p><?= $userData['firstName'] ?? 'Not provided'; ?></p>
            </div>

            <div class="form-group">
                <label>Surname:</label>
                <p><?= $userData['lastName'] ?? 'Not provided'; ?></p>
            </div>

            <!-- Phone Number -->
            <div class="form-group">
                <label>Phone number:</label>
                <p><?= $userData['phoneNumber'] ?? 'Not provided'; ?></p>
            </div>

            <!-- Address -->
            <div class="form-group">
                <label>Address:</label>
                <p><?= $userData['street'] ?? 'Not provided'; ?></p>
            </div>

            <!-- Postal Code -->
            <div class="form-group">
                <label>Postal code:</label>
                <p><?= $userData['postalCode'] ?? 'Not provided'; ?></p>
            </div>

            <!-- City -->
            <div class="form-group">
                <label>City:</label>
                <p><?= $userData['city'] ?? 'Not provided'; ?></p>
            </div>
        </div>



        <!-- Edit Button -->
        <div class="form-actions">
            <a href="edit_user.php" class="user-account-edit-btn">Edit</a>
        </div>
    </div>
</div>



</body>
</html>
