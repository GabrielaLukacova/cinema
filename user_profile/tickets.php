<?php
// Sample query to fetch tickets (replace with your actual logic)
$query = $db->prepare("SELECT * FROM Tickets WHERE userID = :userID");
$query->execute([':userID' => $userID]);
$tickets = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="user-account-content">
    <h2>My Tickets</h2>
    <div class="tickets-list">
        <?php if ($tickets): ?>
            <?php foreach ($tickets as $ticket): ?>
                <div class="ticket-item">
                    <p><strong>Movie:</strong> <?= htmlspecialchars($ticket['movieTitle']); ?></p>
                    <p><strong>Date:</strong> <?= htmlspecialchars($ticket['showDate']); ?></p>
                    <p><strong>Time:</strong> <?= htmlspecialchars($ticket['showTime']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No tickets found.</p>
        <?php endif; ?>
    </div>
</section>
