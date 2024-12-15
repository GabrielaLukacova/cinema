<?php
require_once("../../includes/connection.php");
// require_once "../../navbar_footer/cinema_navbar.php";
$message = "";

// form variables
$firstName = $lastName = $email = $phoneNumber = $password = $confirmPassword = $street = $postalCode = $city = "";

// form submission 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
    $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phoneNumber = isset($_POST['phoneNumber']) ? trim($_POST['phoneNumber']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? trim($_POST['confirmPassword']) : '';
    $street = isset($_POST['street']) ? trim($_POST['street']) : '';
    $postalCode = isset($_POST['postalCode']) ? trim($_POST['postalCode']) : '';
    $city = isset($_POST['city']) ? trim($_POST['city']) : '';

    // check if all required fields are filled and passwords match
    if (
        empty($firstName) || empty($lastName) || empty($email) || empty($password) ||
        empty($confirmPassword) || empty($postalCode) || empty($city)
    ) {
        $message = "Please fill in all required fields.";
    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
    } else {
        // hashing password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $insertPostalQuery = "INSERT IGNORE INTO PostalCode (postalCode, city) VALUES (:postalCode, :city)";
            $stmtPostal = $db->prepare($insertPostalQuery);
            $stmtPostal->bindParam(':postalCode', $postalCode, PDO::PARAM_STR);
            $stmtPostal->bindParam(':city', $city, PDO::PARAM_STR);
            $stmtPostal->execute();

            // insert date into db
            $insertUserQuery = "INSERT INTO User (firstName, lastName, email, phoneNumber, password, street, postalCode) 
                                VALUES (:firstName, :lastName, :email, :phoneNumber, :password, :street, :postalCode)";
            $stmtUser = $db->prepare($insertUserQuery);
            $stmtUser->bindParam(':firstName', $firstName, PDO::PARAM_STR);
            $stmtUser->bindParam(':lastName', $lastName, PDO::PARAM_STR);
            $stmtUser->bindParam(':email', $email, PDO::PARAM_STR);
            $stmtUser->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
            $stmtUser->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmtUser->bindParam(':street', $street, PDO::PARAM_STR);
            $stmtUser->bindParam(':postalCode', $postalCode, PDO::PARAM_STR);

            if ($stmtUser->execute()) {
                echo "<script>window.onload = function() { document.getElementById('successModalTrigger').click(); }</script>";
            }
        } catch (PDOException $e) {
            die("Database query failed: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Create a New Account</h2>

        <form method="POST" action="" class="card p-4 shadow-sm">
            <div class="form-group mb-3">
                <label for="firstName">First Name</label>
                <input type="text" name="firstName" id="firstName" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="lastName">Last Name</label>
                <input type="text" name="lastName" id="lastName" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="phoneNumber">Phone Number</label>
                <input type="text" name="phoneNumber" id="phoneNumber" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="street">Street</label>
                <input type="text" name="street" id="street" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="postalCode">Postal Code</label>
                <input type="text" name="postalCode" id="postalCode" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="city">City</label>
                <input type="text" name="city" id="city" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <input type="submit" value="Create Account" class="btn btn-primary w-100">
            </div>
        </form>
    </div>
<!-- hidden button -->
    <button type="button" id="successModalTrigger" class="d-none" data-bs-toggle="modal" data-bs-target="#successModal"></button>

    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Account created successfully! Please log in.
                </div>
                <div class="modal-footer">
                    <a href="login.php" class="btn btn-primary">Log in</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
