<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../classes/user.php';
require_once '../../includes/connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$userID = $_SESSION['user_id'];
$user = new User();

// Fetch user data
$userData = $user->getUserProfile($userID);
if (!$userData) {
    die("User not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'firstName' => htmlspecialchars(trim($_POST['firstName']), ENT_QUOTES, 'UTF-8'),
        'lastName' => htmlspecialchars(trim($_POST['lastName']), ENT_QUOTES, 'UTF-8'),
        'email' => htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8'),
        'phoneNumber' => htmlspecialchars(trim($_POST['phoneNumber']), ENT_QUOTES, 'UTF-8'),
        'street' => htmlspecialchars(trim($_POST['street']), ENT_QUOTES, 'UTF-8'),
        'postalCode' => htmlspecialchars(trim($_POST['postalCode']), ENT_QUOTES, 'UTF-8'),
        'city' => htmlspecialchars(trim($_POST['city']), ENT_QUOTES, 'UTF-8'),
    ];

    // Validate email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        die('Invalid email address.');
    }

    // Validate postal code format
    if (!preg_match('/^\d{4}$/', $data['postalCode'])) {
        die('Postal code must be a 4-digit numeric value.');
    }

    // Check if postal code exists
    $postalCodeCheckStmt = $db->prepare('SELECT city FROM PostalCode WHERE postalCode = :postalCode');
    $postalCodeCheckStmt->execute(['postalCode' => $data['postalCode']]);
    $postalCodeResult = $postalCodeCheckStmt->fetch(PDO::FETCH_ASSOC);

    if (!$postalCodeResult) {
        // Insert the new postal code with the provided city
        $insertPostalCodeStmt = $db->prepare('INSERT INTO PostalCode (postalCode, city) VALUES (:postalCode, :city)');
        $insertPostalCodeStmt->execute([
            'postalCode' => $data['postalCode'],
            'city' => $data['city'],
        ]);
    } else {
        // Update the city for the existing postal code
        $updatePostalCodeStmt = $db->prepare('UPDATE PostalCode SET city = :city WHERE postalCode = :postalCode');
        $updatePostalCodeStmt->execute([
            'city' => $data['city'],
            'postalCode' => $data['postalCode'],
        ]);
    }

    // Handle profile picture upload
    if (isset($_FILES['userPicture']) && $_FILES['userPicture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../includes/media/users/';
        $fileName = basename($_FILES['userPicture']['name']);
        $filePath = $uploadDir . $fileName;
        $fileType = mime_content_type($_FILES['userPicture']['tmp_name']);
    
        if (in_array($fileType, ['image/jpeg', 'image/png'])) {
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            if (move_uploaded_file($_FILES['userPicture']['tmp_name'], $filePath)) {
                // Add the file name (relative path) to the data array
                $data['userPicture'] = $fileName; // Store only the file name in the database
            } else {
                die('Failed to upload profile picture.');
            }
        } else {
            die('Invalid file type. Only JPEG and PNG are allowed.');
        }
    } else {
        // Ensure userPicture is retained if no new file is uploaded
        $data['userPicture'] = $userData['userPicture'];
    }
    
    // Update user profile
    $user->updateUserProfile($userID, $data);

    // Redirect back to user profile page
    header('Location: ../views/user_data.php');
    exit();
}
?>