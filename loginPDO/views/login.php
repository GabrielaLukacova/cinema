<?php
require_once "../actions/login_logic.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <title>Login</title>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

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
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="pass">Password:</label>
                <input type="password" id="pass" name="pass" required>
            </div>
            <div class="form-actions">
                <button type="submit" name="submit" class="btn-primary">Login</button>
            </div>
        </form>
    </div>
</body>
</html>


