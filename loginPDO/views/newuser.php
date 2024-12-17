<?php
require_once "../actions/newuser_logic.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create account</title>
    <link rel="stylesheet" href="../../css/style.css">
    <!DOCTYPE html>
<html lang="en">
<body>
    <div class="new-user-container">
        <div class="new-user-header">
            <h1>Create a new account</h1>
        </div>

        <form method="POST" action="">
            <div class="new-user-details">
                <ul>
                    <li>
                        <label for="firstName">First name</label>
                        <input type="text" name="firstName" id="firstName" required>
                    </li>
                    <li>
                        <label for="lastName">Last name</label>
                        <input type="text" name="lastName" id="lastName" required>
                    </li>
                    <li>
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" required>
                    </li>
                    <li>
                        <label for="phoneNumber">Phone number</label>
                        <input type="text" name="phoneNumber" id="phoneNumber">
                    </li>
                    <li>
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required>
                    </li>
                    <li>
                        <label for="confirmPassword">Confirm password</label>
                        <input type="password" name="confirmPassword" id="confirmPassword" required>
                    </li>
                    <li>
                        <label for="street">Street</label>
                        <input type="text" name="street" id="street">
                    </li>
                    <li>
                        <label for="postalCode">Postal code</label>
                        <input type="text" name="postalCode" id="postalCode" required>
                    </li>
                    <li>
                        <label for="city">City</label>
                        <input type="text" name="city" id="city" required>
                    </li>
                </ul>
            </div>

            <div class="new-user-footer">
                <button type="submit">Create account</button>
            </div>
        </form>
    </div>
</body>
</html>