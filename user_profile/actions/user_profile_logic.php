<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../loginPDO/login.php');
    exit();
}

require_once '../../includes/connection.php';
require_once '../classes/user.php';

// Initialize variables
$userID = $_SESSION['user_id'];
$user = new User();
$currentPage = basename($_SERVER['PHP_SELF']);

// Fetch and sanitize user data
$userData = $user->getUserProfile($userID);
$userData = array_map(fn($value) => htmlspecialchars($value, ENT_QUOTES, 'UTF-8'), $userData);

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['userPicture']['name'])) {
    $uploadDir = '../includes/media/users/';
    $fileName = basename($_FILES['userPicture']['name']);
    $filePath = $uploadDir . $fileName;
    $fileType = mime_content_type($_FILES['userPicture']['tmp_name']);

    // Validate file type and upload
    if (in_array($fileType, ['image/jpeg', 'image/png'])) {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($_FILES['userPicture']['tmp_name'], $filePath)) {
            $user->updateUserPicture($userID, htmlspecialchars($filePath, ENT_QUOTES, 'UTF-8'));
        } else {
            $uploadError = 'Failed to upload file.';
        }
    } else {
        $uploadError = 'Invalid file type. Only JPEG and PNG are allowed.';
    }
}
?>
