<?php 
session_start();
require_once("functions.php");
require_once("../../includes/connection.php");

$message = ""; // To hold error or success messages

// Handle form submission
if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['pass']);

    try {
        // Query to find the user by email
        $query = "SELECT userID, email, password FROM User WHERE email = :email LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $found_user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists
        if ($found_user) {
            // Verify the password
            if (password_verify($password, $found_user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $found_user['userID'];
                $_SESSION['email'] = $found_user['email'];

                // Redirect to home page after login
                header("Location: ../home.php");
                exit();
            } else {
                // Invalid password
                $message = "Invalid password.";
            }
        } else {
            // No user found with that email
            $message = "No user found with that email.";
        }
    } catch (PDOException $e) {
        // Catch any database errors
        die("Database query failed: " . $e->getMessage());
    }
}

// Display message if there's any
if (!empty($message)) {
    echo "<div class='alert alert-danger'>" . htmlspecialchars($message) . "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Please Login</h2>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Login Form -->
                <form action="" method="post" class="card p-4 shadow-sm">
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" maxlength="100" required />
                    </div>
                    <div class="form-group mb-3">
                        <label for="pass">Password</label>
                        <input type="password" name="pass" id="pass" class="form-control" maxlength="30" required />
                    </div>

                    <div class="form-group mb-3">
                        <input type="submit" name="submit" value="Login" class="btn btn-primary w-100" />
                    </div>
                </form>
                <div class="text-center mt-3">
                    <p>Do not have an account yet?</p>
                    <button onclick="window.location.href='newuser.php';" class="btn btn-link">Create Account</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

