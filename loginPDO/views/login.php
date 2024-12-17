<?php
require_once "../actions/login_logic.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="new-user-container">
        <div class="new-user-header">
            <h1>Login</h1>
        </div>

        <!-- Display error message if any -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . (!empty($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''); ?>" method="POST">
            <div class="new-user-details">
                <ul>
                    <li>
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" required>
                    </li>
                    <li>
                        <label for="pass">Password</label>
                        <input type="password" name="pass" id="pass" required>
                    </li>
                </ul>
            </div>

            <div class="new-user-footer">
                <button type="submit" name="submit">Login</button>
            </div>
        </form>

        <div class="create_account_call_to_action">
        <p>Don't have an account?</p>
        <a class="btn-primary" href="newuser.php">Create account</a>
        </div>
    </div>
</body>
</html>

