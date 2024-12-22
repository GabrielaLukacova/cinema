<?php
require_once '../../includes/connection.php';

class User {
    private $db;

    public function __construct() {
        global $db; // Use the `$db` from your existing `connection.php`
        $this->db = $db;
    }

    // Get user profile 
    public function getUserProfile($userID) {
        if (!$userID) {
            throw new Exception('Invalid user ID.');
        }

        $stmt = $this->db->prepare('
            SELECT u.*, p.city
            FROM User u
            LEFT JOIN PostalCode p ON u.postalCode = p.postalCode
            WHERE userID = :userID
        ');
        $stmt->execute(['userID' => $userID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    public function updateUserProfile($userID, $data) {
        // Validate postalCode exists
        $checkPostalCodeStmt = $this->db->prepare('SELECT city FROM PostalCode WHERE postalCode = :postalCode');
        $checkPostalCodeStmt->execute(['postalCode' => $data['postalCode']]);
        $city = $checkPostalCodeStmt->fetchColumn();
    
        if (!$city) {
            throw new Exception('Invalid postal code.');
        }
    
        // Proceed with update
        $stmt = $this->db->prepare('
            UPDATE User SET
                firstName = :firstName,
                lastName = :lastName,
                email = :email,
                phoneNumber = :phoneNumber,
                street = :street,
                postalCode = :postalCode,
                userPicture = :userPicture
            WHERE userID = :userID
        ');
        $stmt->execute([
            'firstName' => htmlspecialchars($data['firstName']),
            'lastName' => htmlspecialchars($data['lastName']),
            'email' => htmlspecialchars($data['email']),
            'phoneNumber' => htmlspecialchars($data['phoneNumber']),
            'street' => htmlspecialchars($data['street']),
            'postalCode' => htmlspecialchars($data['postalCode']),
            'userPicture' => htmlspecialchars($data['userPicture']),
            'userID' => $userID
        ]);
    }
    
    
    public function updateUserPicture($userID, $imagePath) {
        try {
            // Ensure $this->db is valid
            if (!$this->db) {
                throw new Exception("Database connection is not initialized.");
            }
    
            // Prepare the statement
            $stmt = $this->db->prepare('
                UPDATE User 
                SET userPicture = :userPicture
                WHERE userID = :userID
            ');
    
            // Execute the query with bound parameters
            $stmt->execute([
                'userPicture' => htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8'),
                'userID' => $userID
            ]);
        } catch (PDOException $e) {
            // Log or display the error
            error_log("Database Error: " . $e->getMessage());
            die("Failed to update user picture: " . $e->getMessage());
        } catch (Exception $e) {
            // Handle general exceptions
            error_log("General Error: " . $e->getMessage());
            die("An unexpected error occurred: " . $e->getMessage());
        }
    }
    
}
