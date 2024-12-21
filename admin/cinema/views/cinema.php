<?php 
require_once "../../components/views/admin_navbar.php"; 
require_once "../actions/cinema_logic.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Cinema</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../admin_style/admin_style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container my-4">
    <div class="shadow rounded bg-white p-4">
        <h2 class="text-center mb-4">Cinema Details</h2>
        <?php if ($cinema): ?>
            <div class="mb-3">
                <h5>Name:</h5>
                <p><?= htmlspecialchars($cinema['name']); ?></p>
            </div>
            <div class="mb-3">
                <h5>Phone Number:</h5>
                <p><?= htmlspecialchars($cinema['phoneNumber']); ?></p>
            </div>
            <div class="mb-3">
                <h5>Email:</h5>
                <p><?= htmlspecialchars($cinema['email']); ?></p>
            </div>
            <div class="mb-3">
                <h5>Street:</h5>
                <p><?= htmlspecialchars($cinema['street']); ?></p>
            </div>
            <div class="mb-3">
                <h5>Postal Code:</h5>
                <p><?= htmlspecialchars($cinema['postalCode']); ?></p>
            </div>
            <div class="mb-3">
                <h5>City:</h5>
                <p><?= htmlspecialchars($cinema['city']); ?></p>
            </div>
            <div class="mb-3">
                <h5>Description:</h5>
                <p><?= htmlspecialchars($cinema['description']); ?></p>
            </div>
            
            <!-- Display Opening Hours -->
            <div class="mb-3">
                <h5>Opening Hours:</h5>
                <ul>
                    <?php foreach ($openingHours as $hours): ?>
                        <li>
                            <strong><?= htmlspecialchars($hours['dayOfWeek']); ?>:</strong>
                            <?= htmlspecialchars($hours['openingTime']); ?> - <?= htmlspecialchars($hours['closingTime']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="text-center mt-4">
                <a href="edit_cinema.php?cinemaID=1" class="btn btn-primary">Edit</a>
            </div>
        <?php else: ?>
            <p class="text-center">No cinema details found.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
