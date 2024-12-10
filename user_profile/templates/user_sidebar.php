<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin navbar/title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head> -->



<?php
require_once 'classes/user.php';
?>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="../../css/style.css">


<?php
$userID = $_SESSION['user_id'];
$user = new User();

// Fetch and sanitize user data
$userData = $user->getUserProfile($userID);
$userData = array_map(fn($value) => htmlspecialchars($value, ENT_QUOTES, 'UTF-8'), $userData);


$currentPage = basename($_SERVER['PHP_SELF']); // Get the current page



if (!empty($_FILES['userPicture']['name'])) {
    $uploadDir = '../includes/media/users/';
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
?>


    <ul class="user-account-sidebar">
        <li class="user-nav-item">
            <a href="views/tickets.php" class="user-account-sidebar-link <?= $currentPage == 'views/tickets.php' ? 'active' : ''; ?>">
                <span class="material-icons">local_activity</span> My tickets
            </a>
        </li>
        <li class="user-nav-item">
            <a href="views/user_data.php" class="user-account-sidebar-link <?= $currentPage == '../views/user_data.php' ? 'active' : ''; ?>">
                <span class="material-icons">face</span> Personal data
            </a>
        </li>
        <li class="user-nav-item">
            <a href="../../loginPDO/logout.php" class="user-account-sidebar-link <?= $currentPage == '../loginPDO/logout.php' ? 'active' : ''; ?>">
                <span class="material-icons">logout</span> Log out
            </a>
        </li>
    </ul>

<section class="user-account-profile">
    <img src="<?= htmlspecialchars($userData['userPicture'] ?? '../../includes/media/other/user_default.png'); ?>" alt="Profile Picture" class="user-account-avatar">
    <h2 class="user-account-name">
        <?= htmlspecialchars(($userData['firstName'] ?? '') . ' ' . ($userData['lastName'] ?? '')); ?>
    </h2>
</section>