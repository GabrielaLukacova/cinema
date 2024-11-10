<?php
require_once("../../includes/connection.php");
$message = "";

// Initialize form variables
$firstName = $lastName = $email = $phoneNumber = $password = $confirmPassword = $street = $postalCode = $city = "";

// Handle form submission
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

    // Check if all required fields are filled and passwords match
    if (
        empty($firstName) || empty($lastName) || empty($email) || empty($password) ||
        empty($confirmPassword) || empty($postalCode) || empty($city)
    ) {
        $message = "Please fill in all required fields.";
    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
    } else {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Insert the postal code and city if not exists
            $insertPostalQuery = "INSERT IGNORE INTO UserPostalCode (postalCode, city) VALUES (:postalCode, :city)";
            $stmtPostal = $db->prepare($insertPostalQuery);
            $stmtPostal->bindParam(':postalCode', $postalCode, PDO::PARAM_STR);
            $stmtPostal->bindParam(':city', $city, PDO::PARAM_STR);
            $stmtPostal->execute();

            // Insert the user data into the User table
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

            $stmtUser->execute();

            $message = "Account created successfully! Please <a href='login.php'>log in</a>.";
        } catch (PDOException $e) {
            $message = "Error: " . htmlspecialchars($e->getMessage());
        }
    }
}

// Display message if any
if (!empty($message)) {
    echo "<div class='alert alert-info'>" . htmlspecialchars($message) . "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Create New Account</h2>
        <form action="" method="post" class="card p-4 shadow-sm">
            <div class="form-group mb-3">
                <label for="firstName">First Name</label>
                <input type="text" name="firstName" id="firstName" class="form-control" value="<?php echo htmlspecialchars($firstName); ?>" required>
            </div>
            <div class="form-group mb-3">
                <label for="lastName">Last Name</label>
                <input type="text" name="lastName" id="lastName" class="form-control" value="<?php echo htmlspecialchars($lastName); ?>" required>
            </div>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group mb-3">
                <label for="phoneNumber">Phone Number</label>
                <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" value="<?php echo htmlspecialchars($phoneNumber); ?>">
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
                <input type="text" name="street" id="street" class="form-control" value="<?php echo htmlspecialchars($street); ?>">
            </div>
            <div class="form-group mb-3">
                <label for="postalCode">Postal Code</label>
                <input type="text" name="postalCode" id="postalCode" class="form-control" value="<?php echo htmlspecialchars($postalCode); ?>" required>
            </div>
            <div class="form-group mb-3">
                <label for="city">City</label>
                <input type="text" name="city" id="city" class="form-control" value="<?php echo htmlspecialchars($city); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Account</button>
        </form>
    </div>
</body>
</html>

