<?php 
require_once("../../includes/connection.php"); 
include '../includes/admin_navbar.php'; 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Cinema</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../includes/admin_style.css?v=1.2">


    <?php
// Fetching from db
$query = $db->prepare("
    SELECT c.*, cp.city 
    FROM Cinema c 
    LEFT JOIN CinemaPostalCode cp ON c.postalCode = cp.postalCode 
    LIMIT 1
");
$query->execute();
$cinema = $query->fetch(PDO::FETCH_ASSOC);
?>

<body>
<div class="table p-4 shadow rounded bg-white">
    <div class="border-0 mb-4">
        <div class="text-center">
            
            <h2 class="mb-0">Cinema details</h2>
        </div>
        <div class="card-body">
            <?php if ($cinema): ?>
                <div class="mb-3">
                    <h5>Name:</h5>
                    <p><?php echo htmlspecialchars($cinema['name']); ?></p>
                </div>
                <div class="mb-3">
                    <h5>Phone number:</h5>
                    <p><?php echo htmlspecialchars($cinema['phoneNumber']); ?></p>
                </div>
                <div class="mb-3">
                    <h5>Email:</h5>
                    <p><?php echo htmlspecialchars($cinema['email']); ?></p>
                </div>
                <div class="mb-3">
                    <h5>Street:</h5>
                    <p><?php echo htmlspecialchars($cinema['street']); ?></p>
                </div>
                <div class="mb-3">
                    <h5>Postal code:</h5>
                    <p><?php echo htmlspecialchars($cinema['postalCode']); ?></p>
                </div>
                <div class="mb-3">
                    <h5>City:</h5>
                    <p><?php echo htmlspecialchars($cinema['city']); ?></p>
                </div>
                <div class="mb-3">
                    <h5>Description:</h5>
                    <p><?php echo htmlspecialchars($cinema['description']); ?></p>
                </div>
                <div class="text-center mt-4">
                    <a href="editCinema.php?cinemaID=1" class="btn btn-primary">Edit</a>
                </div>
            <?php else: ?>
                <p class="text-center">No cinema details found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>