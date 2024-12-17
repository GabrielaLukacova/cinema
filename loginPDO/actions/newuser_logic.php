<?php
require_once "../../includes/connection.php";

$message = "";

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize inputs
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phoneNumber = trim($_POST['phoneNumber'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirmPassword'] ?? '');
    $street = trim($_POST['street'] ?? '');
    $postalCode = trim($_POST['postalCode'] ?? '');
    $city = trim($_POST['city'] ?? '');

    // Validate inputs
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword) || empty($postalCode) || empty($city)) {
        $message = "Please fill in all required fields.";
    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Insert postal code and city into the database (if not exists)
            $insertPostalQuery = "
                INSERT IGNORE INTO PostalCode (postalCode, city) 
                VALUES (:postalCode, :city)
            ";
            $stmtPostal = $db->prepare($insertPostalQuery);
            $stmtPostal->execute([
                ':postalCode' => $postalCode,
                ':city' => $city
            ]);

            // Insert user data into the database
            $insertUserQuery = "
                INSERT INTO User (firstName, lastName, email, phoneNumber, password, street, postalCode) 
                VALUES (:firstName, :lastName, :email, :phoneNumber, :password, :street, :postalCode)
            ";
            $stmtUser = $db->prepare($insertUserQuery);
            $stmtUser->execute([
                ':firstName' => $firstName,
                ':lastName' => $lastName,
                ':email' => $email,
                ':phoneNumber' => $phoneNumber,
                ':password' => $hashedPassword,
                ':street' => $street,
                ':postalCode' => $postalCode
            ]);

            // Success message
            header("Location: login.php?success=1");
            exit();
        } catch (PDOException $e) {
            $message = "Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }
}
?>
