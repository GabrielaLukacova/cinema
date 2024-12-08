<?php
require_once '../loginPDO/session.php'; // Include session management
require_once 'classes/user.php';
include '../navbar_footer/cinema_navbar.php';

// Ensure the user is logged in
confirm_logged_in();

// Retrieve the user ID from the session
$userID = $_SESSION['user_id'] ?? null; // Adjusted to match the `session.php` naming

// Ensure the user ID is valid
if (!$userID) {
    die('<p>Error: Invalid session. Please log in again.</p>');
}

$user = new User();
$userData = $user->getUserProfile($userID);

// Handle missing or invalid user data
if (!$userData) {
    die('<p>Error: Unable to load user profile. Please contact support.</p>');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>User Profile</title>
</head>
<body>
    <div class="user-account-container">
        <div class="user-account-sidebar">
            <a href="#" class="user-account-sidebar-link">My Tickets</a>
            <a href="user_profile.php" class="user-account-sidebar-link active">Personal Data</a>
            <a href="logout.php" class="user-account-sidebar-link">Log Out</a>
        </div>
        <!-- <div class="user-account-main">
            <div class="user-account-profile">
                <img src="<?= htmlspecialchars($userData['userPicture'] ?? '../uploads/user_images/default-avatar.png'); ?>" alt="Profile Picture" class="user-account-avatar">
                <h2 class="user-account-name">
                    <?= htmlspecialchars(($userData['firstName'] ?? '') . ' ' . ($userData['lastName'] ?? '')); ?>
                </h2>
                <p>Email: <?= htmlspecialchars($userData['email'] ?? 'N/A'); ?></p>
                <p>Phone: <?= htmlspecialchars($userData['phoneNumber'] ?? 'N/A'); ?></p>
                <p>Address: <?= htmlspecialchars($userData['street'] ?? 'N/A'); ?></p>
                <p>City: <?= htmlspecialchars($userData['city'] ?? 'N/A'); ?></p>
                <p>Postal Code: <?= htmlspecialchars($userData['postalCode'] ?? 'N/A'); ?></p>
                <a href="user_data.php" class="user-account-edit-btn">Edit</a>
            </div>
        </div>
    </div> -->
</body>
</html>