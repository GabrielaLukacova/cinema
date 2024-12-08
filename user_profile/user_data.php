<?php
require_once 'classes/user.php';

session_start();
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit;
}

$userID = $_SESSION['userID'];
$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'firstName' => htmlspecialchars($_POST['firstName']),
        'lastName' => htmlspecialchars($_POST['lastName']),
        'phoneNumber' => htmlspecialchars($_POST['phoneNumber']),
        'street' => htmlspecialchars($_POST['street']),
        'postalCode' => htmlspecialchars($_POST['postalCode'])
    ];

    $user->updateUserProfile($userID, $data);

    // Handle image upload securely
    if (!empty($_FILES['userPicture']['name'])) {
        $uploadDir = '../uploads/user_images/';
        $fileName = basename($_FILES['userPicture']['name']);
        $filePath = $uploadDir . $fileName;
        $fileType = mime_content_type($_FILES['userPicture']['tmp_name']);

        if (in_array($fileType, ['image/jpeg', 'image/png'])) {
            if (move_uploaded_file($_FILES['userPicture']['tmp_name'], $filePath)) {
                $user->updateUserPicture($userID, $filePath);
            } else {
                die('Failed to upload file.');
            }
        } else {
            die('Invalid file type. Only JPEG and PNG are allowed.');
        }
    }

    header('Location: user_profile.php');
    exit;
}

$userData = $user->getUserProfile($userID);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Edit User Data</title>
</head>
<body>
    <div class="user-account-container">
        <div class="user-account-sidebar">
            <a href="#" class="user-account-sidebar-link">My Tickets</a>
            <a href="user_profile.php" class="user-account-sidebar-link">Personal Data</a>
            <a href="logout.php" class="user-account-sidebar-link">Log Out</a>
        </div>
        <div class="user-account-main">
            <form action="user_data.php" method="post" enctype="multipart/form-data" class="user-account-personal-data">
                <label>First Name:</label>
                <input type="text" name="firstName" value="<?= htmlspecialchars($userData['firstName']); ?>" required>

                <label>Last Name:</label>
                <input type="text" name="lastName" value="<?= htmlspecialchars($userData['lastName']); ?>" required>

                <label>Phone Number:</label>
                <input type="text" name="phoneNumber" value="<?= htmlspecialchars($userData['phoneNumber']); ?>" required>

                <label>Street:</label>
                <input type="text" name="street" value="<?= htmlspecialchars($userData['street']); ?>" required>

                <label>Postal Code:</label>
                <input type="text" name="postalCode" value="<?= htmlspecialchars($userData['postalCode']); ?>" required>

                <label>Profile Picture:</label>
                <input type="file" name="userPicture" accept="image/jpeg, image/png">

                <button type="submit" class="user-account-edit-btn">Save Changes</button>
            </form>
        </div>
    </div>
</body>
</html>
