<!-- <?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection
require_once "../../includes/connection.php";

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$userID = $_SESSION['user_id'];

try {
    // Prepare and execute the query securely
    $query = $db->prepare("
        SELECT 
            movieTitle,
            movieImage,
            showDate,
            showTime,
            roomNumber,
            ticketPrice,
            seatDetails
        FROM UserTickets
        WHERE userID = :userID
        ORDER BY showDate DESC, showTime DESC
    ");
    
    $query->bindParam(':userID', $userID, PDO::PARAM_INT);
    $query->execute();

    // Fetch all tickets
    $tickets = $query->fetchAll(PDO::FETCH_ASSOC);

    // If no tickets are found, set to an empty array
    if (!$tickets) {
        $tickets = [];
    }
} catch (PDOException $e) {
    // Log the error and set tickets to an empty array
    error_log("Database error: " . $e->getMessage());
    $tickets = [];
}
?> -->
