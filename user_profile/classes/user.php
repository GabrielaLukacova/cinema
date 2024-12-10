<?php
require_once '../includes/connection.php';

class User {
    private $db;

    public function __construct() {
        global $db; // Use the `$db` from your existing `connection.php`
        $this->db = $db;
    }

    public function getUserProfile($userID) {
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
        $stmt = $this->db->prepare('
            UPDATE User SET
                firstName = :firstName,
                lastName = :lastName,
                phoneNumber = :phoneNumber,
                street = :street,
                postalCode = :postalCode
            WHERE userID = :userID
        ');
        $stmt->execute([
            'firstName' => htmlspecialchars($data['firstName']),
            'lastName' => htmlspecialchars($data['lastName']),
            'phoneNumber' => htmlspecialchars($data['phoneNumber']),
            'street' => htmlspecialchars($data['street']),
            'postalCode' => htmlspecialchars($data['postalCode']),
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
