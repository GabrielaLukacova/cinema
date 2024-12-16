<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if user is not logged in
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

// Set default values for missing fields
if (!$userData) {
    $userData = [
        'userPicture' => '../../includes/media/other/user_default.png',
        'firstName' => 'Guest',
        'lastName' => 'User',
    ];
}

// Apply htmlspecialchars only on non-null values
$userData = array_map(
    fn($value) => $value !== null ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : '',
    $userData
);

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['userPicture']['name'])) {
    $uploadDir = '../../includes/media/users/';
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
            $userData['userPicture'] = htmlspecialchars($filePath, ENT_QUOTES, 'UTF-8');
        } else {
            echo '<div class="alert alert-danger">Failed to upload file.</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Invalid file type. Only JPEG and PNG are allowed.</div>';
    }
}
?>

<!-- Sidebar HTML -->
<ul class="user-account-sidebar">
    <li class="user-nav-item">
        <a href="../views/tickets.php" class="user-account-sidebar-link <?= $currentPage === 'tickets.php' ? 'active' : ''; ?>">
            <span class="material-icons">local_activity</span> My tickets
        </a>
    </li>
    <li class="user-nav-item">
        <a href="../views/user_data.php" class="user-account-sidebar-link <?= $currentPage === 'user_data.php' ? 'active' : ''; ?>">
            <span class="material-icons">face</span> Personal data
        </a>
    </li>
    <li class="user-nav-item">
        <a href="../../loginPDO/actions/logout.php" class="user-account-sidebar-link">
            <span class="material-icons">logout</span> Log out
        </a>
    </li>
</ul>




<!-- User Profile Section -->
<section class="user-account-profile">
    <img src="<?= $userData['userPicture'] ?>" alt="Profile Picture" class="user-account-avatar">
    <h2 class="user-account-name"><?= $userData['firstName'] . ' ' . $userData['lastName']; ?></h2>
</section>
