<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../loginPDO/login.php');
    exit();
}

require_once '../classes/user.php';

// Initialize user and current page
$userID = $_SESSION['user_id'];
$user = new User();
$currentPage = basename($_SERVER['PHP_SELF']);

// Fetch user data
$userData = $user->getUserProfile($userID);
if (!$userData) {
    die('Error: User not found.');
}

// Sanitize user data
// $userData = array_map(
//     fn($value) => htmlspecialchars($value, ENT_QUOTES, 'UTF-8'),
//     $userData
// );

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['userPicture'])) {
    $uploadDir = '../../includes/media/users/';
    $fileName = basename($_FILES['userPicture']['name']);
    $filePath = $uploadDir . $fileName;

    // Validate file type
    $fileType = mime_content_type($_FILES['userPicture']['tmp_name']);
    if (in_array($fileType, ['image/jpeg', 'image/png'])) {
        // Create upload directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES['userPicture']['tmp_name'], $filePath)) {
            $filePathSanitized = htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8'); // Store only the filename
            $user->updateUserPicture($userID, $filePathSanitized);

            // Update the userData array with the new profile picture
            $userData['userPicture'] = $filePathSanitized;
        } else {
            $errorMessage = 'Failed to upload the file.';
        }
    } else {
        $errorMessage = 'Invalid file type. Only JPEG and PNG files are allowed.';
    }
}

// Ensure the profile picture is displayed only if it exists in the database
$uploadDir = '../../includes/media/users/';
$profilePicture = !empty($userData['userPicture']) ? $uploadDir . $userData['userPicture'] : '../../includes/media/other/user_default.png';
?>
