<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("../actions/functions.php");
require_once("../../includes/connection.php");

$message = ""; // Initialize error message

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['pass']);

    try {
        // Fetch user data
        $query = "SELECT userID, email, password FROM User WHERE email = :email LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $found_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($found_user) {
            if (password_verify($password, $found_user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $found_user['userID'];
                $_SESSION['email'] = $found_user['email'];

                // Redirect to intended page or default to tickets
                $redirect = !empty($_GET['redirect']) ? $_GET['redirect'] : '../../user_profile/views/tickets.php';
                header("Location: " . htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8'));
                exit();
            } else {
                $message = "Invalid password.";
            }
        } else {
            $message = "No user found with that email.";
        }
    } catch (PDOException $e) {
        error_log("Database query failed: " . $e->getMessage());
        $message = "An error occurred. Please try again later.";
    }
}
?>
