<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../classes/user.php';
require_once '../../includes/connection.php';
include '../../navbar_footer/cinema_navbar.php';
include '../templates/user_sidebar.php';

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Edit Profile</title>
</head>
<body>
    <div class="user-account-main">
        <div class="user-account-data">
            <div class="user-account-form-container">
                <div class="edit-profile-container">
                    <form action="../actions/edit_user_logic.php" method="POST" enctype="multipart/form-data" class="edit-profile-form">
                        <h2>Edit My Profile</h2>

                        <div class="form-group">
                            <label for="firstName">First Name:</label>
                            <input type="text" id="firstName" name="firstName" 
                                   value="<?= htmlspecialchars($userData['firstName'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="lastName">Last Name:</label>
                            <input type="text" id="lastName" name="lastName" 
                                   value="<?= htmlspecialchars($userData['lastName'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" 
                                   value="<?= htmlspecialchars($userData['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="phoneNumber">Phone Number:</label>
                            <input type="text" id="phoneNumber" name="phoneNumber" 
                                   value="<?= htmlspecialchars($userData['phoneNumber'], ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="form-group">
                            <label for="street">Street:</label>
                            <input type="text" id="street" name="street" 
                                   value="<?= htmlspecialchars($userData['street'], ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="form-group">
                            <label for="postalCode">Postal Code:</label>
                            <input type="text" id="postalCode" name="postalCode" 
                                   value="<?= htmlspecialchars($userData['postalCode'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="userPicture">Profile Picture:</label>
                            <input type="file" id="userPicture" name="userPicture" accept="image/jpeg, image/png">
                        </div>

                        <div class="form-actions">
                            <a href="user_data.php" class="btn-primary">Cancel</a>
                            <button type="submit" class="btn-secondary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
