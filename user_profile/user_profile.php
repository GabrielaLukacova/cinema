<?php
require_once '../includes/connection.php';
require_once 'classes/user.php';


$userID = 1; // Replace with dynamic ID after implementing login
$query = $db->prepare("
    SELECT u.*, pc.city 
    FROM User u
    LEFT JOIN PostalCode pc ON u.postalCode = pc.postalCode
    WHERE u.userID = :userID
");
$query->execute([':userID' => $userID]);
$userData = $query->fetch(PDO::FETCH_ASSOC);

if ($userData) {
    $user = new User($userData);
} else {
    die("User not found.");
}

// Determine the active tab
$activeTab = $_GET['page'] ?? 'user_data';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>User Profile</title>
</head>
<div class="user-account">
    <div class="user-account-container">
        <!-- Left Sidebar -->
        <aside class="user-account-sidebar">
            <a href="user_profile.php?page=tickets" class="user-account-sidebar-link <?= $activeTab === 'tickets' ? 'active' : ''; ?>">My Tickets</a>
            <a href="user_profile.php?page=user_data" class="user-account-sidebar-link <?= $activeTab === 'personal_data' ? 'active' : ''; ?>">Personal Data</a>
            <a href="../loginPDO/logout.php" class="user-account-sidebar-link">Log Out</a>
        </aside>

        <!-- Profile Content -->
        <div class="user-account-main">
            <?php if ($activeTab === 'tickets'): ?>
                <div class="tickets-panel">
                    <h3>My Tickets</h3>
                    <p>Tickets for upcoming shows will be displayed here.</p>
                    <!-- Replace with your dynamic ticket data -->
                </div>
            <?php elseif ($activeTab === 'user_data'): ?>
                <div class="user-data-panel">
                    <div class="profile-header">
                        <img src="<?= htmlspecialchars($user->imagePath ?: '../assets/images/user-placeholder.png'); ?>" alt="User Avatar" class="user-account-avatar">
                        <h2><?= htmlspecialchars($user->firstName) . ' ' . htmlspecialchars($user->lastName); ?></h2>
                    </div>
                    <div class="user-info-grid">
                        <div>
                            <strong>Name:</strong>
                            <span><?= htmlspecialchars($user->firstName); ?></span>
                        </div>
                        <div>
                            <strong>Surname:</strong>
                            <span><?= htmlspecialchars($user->lastName); ?></span>
                        </div>
                        <div>
                            <strong>Phone Number:</strong>
                            <span><?= htmlspecialchars($user->phoneNumber); ?></span>
                        </div>
                        <div>
                            <strong>Email:</strong>
                            <span><?= htmlspecialchars($user->email); ?></span>
                        </div>
                        <div>
                            <strong>Address:</strong>
                            <span><?= htmlspecialchars($user->street); ?></span>
                        </div>
                        <div>
                            <strong>Postal Code:</strong>
                            <span><?= htmlspecialchars($user->postalCode); ?></span>
                        </div>
                        <div>
                            <strong>City:</strong>
                            <span><?= htmlspecialchars($user->city); ?></span>
                        </div>
                    </div>
                    <form action="edit_user_profile.php" method="post">
                        <button type="submit" class="btn-primary">Edit</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </div>