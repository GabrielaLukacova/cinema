<?php 
require_once "../actions/edit_showtime.php"; 
require_once "../../components/views/admin_navbar.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit showtime</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../../admin_style/admin_style.css?v=1.2">
</head>
<body>
<div class="container my-4">
    <h3 class="mt-5">Edit showtime</h3>

    <?php if (isset($errorMessage)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($errorMessage); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="edit_showtime_view.php?showTimeID=<?= htmlspecialchars($showTimeID); ?>" class="p-4 shadow rounded bg-white">
        <!-- Movie Dropdown -->
        <div class="form-group">
            <label for="movie">Select movie</label>
            <select id="movie" name="movieID" class="form-control" required>
                <option value="">Select a movie</option>
                <?php foreach ($movies as $movie): ?>
                    <option value="<?= htmlspecialchars($movie['movieID']); ?>"
                        <?= $movie['movieID'] == $showTime['movieID'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($movie['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Date Input -->
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" id="date" name="date" class="form-control" 
                   value="<?= htmlspecialchars($showTime['date'] ?? ''); ?>" required>
        </div>

        <!-- Time Input -->
        <div class="form-group">
            <label for="time">Time</label>
            <input type="time" id="time" name="time" class="form-control" 
                   value="<?= htmlspecialchars($showTime['time'] ?? ''); ?>" required>
        </div>

        <!-- Room Input -->
        <div class="form-group">
            <label for="room">Room</label>
            <input type="text" id="room" name="room" class="form-control" 
                   value="<?= htmlspecialchars($showTime['room'] ?? ''); ?>" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" name="submit" class="btn btn-warning btn-block">Update showtime</button>
    </form>
</div>

<?php if (isset($_GET['status'])): ?>
    <div class="alert alert-<?php echo $_GET['status'] === 'updated' ? 'success' : 'danger'; ?>">
        <?= $_GET['status'] === 'updated' ? 'Showtime updated successfully!' : htmlspecialchars($_GET['message'] ?? ''); ?>
    </div>
<?php endif; ?>
</body>
</html>
