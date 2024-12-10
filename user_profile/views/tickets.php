<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once "../../includes/connection.php";

// Ensure user is logged in
$userID = $_SESSION['user_id'] ?? null;
if (!$userID) {
    die("Error: User not logged in.");
}

// Fetch user's bookings
$stmt = $db->prepare("
    SELECT b.bookingID, m.title, st.date, st.time, st.room, s.seatNumber, s.seatRow
    FROM Booking b
    JOIN ShowTime st ON b.showTimeID = st.showTimeID
    JOIN Movie m ON st.movieID = m.movieID
    JOIN Reserves r ON b.bookingID = r.bookingID
    JOIN Seat s ON r.seatID = s.seatID
    WHERE b.userID = :userID
    ORDER BY st.date, st.time
");
$stmt->execute([':userID' => $userID]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Tickets</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <h1>My Tickets</h1>
    <?php if (!empty($tickets)): ?>
        <?php foreach ($tickets as $ticket): ?>
            <div class="ticket">
                <p>Movie: <?= htmlspecialchars($ticket['title']); ?></p>
                <p>Date: <?= htmlspecialchars($ticket['date']); ?></p>
                <p>Time: <?= htmlspecialchars($ticket['time']); ?></p>
                <p>Room: <?= htmlspecialchars($ticket['room']); ?></p>
                <p>Seat: Row <?= htmlspecialchars($ticket['seatRow']); ?>, Seat <?= htmlspecialchars($ticket['seatNumber']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No tickets found.</p>
    <?php endif; ?>
</body>
</html>
