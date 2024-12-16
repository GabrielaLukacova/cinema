<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: ../../dashboard/views/dashboard.php");
    exit();
}

// Hardcoded login
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD_HASH', '$2y$10$HlnE5phmgeKpJFBw9IzcH.kkcZI5U4kE2u54aZL7Ahi6Ualfr4c.C'); 

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate credentials
    if ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD_HASH)) {
        session_regenerate_id(true); // Prevent session fixation
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;

        // Redirect to the dashboard
        header("Location: ../../dashboard/views/dashboard.php");
        exit();
    } else {
        $message = "Invalid username or password.";
    }
}
?>
