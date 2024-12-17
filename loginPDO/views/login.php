<?php
require_once "../actions/login_logic.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+ua7Kw1TIq0kAoFfqA+HNSm9aJb6Y3yL73nXl5F2xVtP4eXSoCH7rwmpo5" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="login-container border p-4 rounded shadow-sm" style="max-width: 400px; width: 100%; background-color: #f9f9f9;">
            <h2 class="text-center mb-4">Login</h2>

            <!-- Display error message if any -->
            <?php if (!empty($message)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . (!empty($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''); ?>" method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="pass">Password:</label>
                    <input type="password" id="pass" name="pass" class="form-control" required>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center">
                    <button type="submit" name="submit" class="btn btn-primary">Login</button>
                    <a href="newuser.php" class="btn btn-link">Don't have an account? Create Account</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zyPx7Q6iA1eKkB5JRIwLqaF2t6zO21d2l3g6zJlM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.5/dist/umd/popper.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0kAoFfqA+HNSm9aJb6Y3yL73nXl5F2xVtP4eXSoCH7rwmpo5" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0kAoFfqA+HNSm9aJb6Y3yL73nXl5F2xVtP4eXSoCH7rwmpo5" crossorigin="anonymous"></script>
</body>
</html>

