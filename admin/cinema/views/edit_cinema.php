<?php 
require_once "../actions/edit_cinema_logic.php"; 
require_once "../../components/views/admin_navbar.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit cinema details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../../admin_style/admin_style.css">
</head>
<body>
<div class="container my-4">
    <h3 class="mt-5">Edit cinema details</h3>

    <form method="post" action="edit_cinema.php?cinemaID=<?= htmlspecialchars($cinemaID); ?>" class="p-4 shadow rounded bg-white">
        <!-- Cinema Details -->
        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($cinema['name']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="phoneNumber">Phone number</label>
            <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" value="<?= htmlspecialchars($cinema['phoneNumber']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($cinema['email']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="street">Street</label>
            <input type="text" id="street" name="street" class="form-control" value="<?= htmlspecialchars($cinema['street']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="postalCode">Postal code</label>
            <input type="text" id="postalCode" name="postalCode" class="form-control" value="<?= htmlspecialchars($cinema['postalCode']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" required><?= htmlspecialchars($cinema['description']); ?></textarea>
        </div>

        <!-- Opening Hours Section -->
        <h4>Opening hours</h4>
        <?php foreach ($openingHours as $hours): ?>
            <div class="form-group mb-3">
                <label for="openingTime_<?= $hours['dayOfWeek']; ?>"><?= htmlspecialchars($hours['dayOfWeek']); ?> Opening time</label>
                <input type="time" id="openingTime_<?= $hours['dayOfWeek']; ?>" name="openingTime[<?= $hours['dayOfWeek']; ?>]" class="form-control" value="<?= htmlspecialchars($hours['openingTime']); ?>" required>
            </div>
            <div class="form-group mb-3">
                <label for="closingTime_<?= $hours['dayOfWeek']; ?>"><?= htmlspecialchars($hours['dayOfWeek']); ?> Closing time</label>
                <input type="time" id="closingTime_<?= $hours['dayOfWeek']; ?>" name="closingTime[<?= $hours['dayOfWeek']; ?>]" class="form-control" value="<?= htmlspecialchars($hours['closingTime']); ?>" required>
            </div>
        <?php endforeach; ?>

        <div class="text-center">
            <button type="submit" class="btn btn-success">Update cinema</button>
        </div>
    </form>
</div>
</body>
</html>





