<?php 
require_once "../../includes/connection.php"; 
require_once "../components/admin_navbar.php"; 
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
    <link rel="stylesheet" href="../admin_style/admin_style.css?v=1.2">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container my-4">
    <h3 class="mt-5">Edit Showtime</h3>

    <?php


    // Retrieve showtime data
    $showTimeID = $_GET['showTimeID'] ?? null;
    if ($showTimeID) {
        $query = $db->prepare("SELECT * FROM ShowTime WHERE showTimeID = :showTimeID");
        $query->execute([':showTimeID' => $showTimeID]);
        $showTime = $query->fetch(PDO::FETCH_ASSOC);

        if (!$showTime) {
            echo "<div class='alert alert-danger'>Showtime not found.</div>";
            exit;
        }
    }

    // Fetch movies
    $moviesQuery = $db->query("SELECT movieID, title FROM Movie");
    $movies = $moviesQuery->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <form method="post" action="edit_showtime.php?showTimeID=<?php echo $showTimeID; ?>" class="p-4 shadow rounded bg-white">
        <!-- Movie Dropdown -->
        <div class="form-group">
            <label for="movie">Select Movie</label>
            <select id="movie" name="movieID" class="form-control" required>
                <option value="">Select a movie</option>
                <?php foreach ($movies as $movie): ?>
                    <option value="<?php echo $movie['movieID']; ?>"
                        <?php echo ($movie['movieID'] == $showTime['movieID']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($movie['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Date Input with Icon -->
        <div class="form-group input-icon">
            <label for="date">Date</label>
            <input type="date" id="date" name="date" class="form-control" 
                   value="<?php echo htmlspecialchars($showTime['date'] ?? ''); ?>" required>
        </div>

        <!-- Time Input with Icon -->
        <div class="form-group input-icon">
            <label for="time">Time</label>
            <input type="time" id="time" name="time" class="form-control" 
                   value="<?php echo htmlspecialchars($showTime['time'] ?? ''); ?>" required>
        </div>

        <!-- Room Input -->
        <div class="form-group">
            <label for="room">Room</label>
            <input type="text" id="room" name="room" class="form-control" 
                   value="<?php echo htmlspecialchars($showTime['room'] ?? ''); ?>" placeholder="Enter room" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" name="submit" class="btn btn-warning btn-block">Update Showtime
        </button>
        <input type="hidden" name="showTimeID" value="<?php echo htmlspecialchars($showTimeID); ?>">
    </form>

    <?php
    // Handle form submission
    if (isset($_POST['submit'])) {
        $movieID = $_POST['movieID'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $room = $_POST['room'];

        $updateQuery = $db->prepare("UPDATE ShowTime SET movieID = :movieID, date = :date, time = :time, room = :room WHERE showTimeID = :showTimeID");
        $executeResult = $updateQuery->execute([
            ':movieID' => $movieID,
            ':date' => $date,
            ':time' => $time,
            ':room' => $room,
            ':showTimeID' => $showTimeID
        ]);

        if ($executeResult) {
            header("Location: movie_calendar.php?status=updated");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Error updating showtime: ";
            print_r($updateQuery->errorInfo());
            echo "</div>";
        }
    }
    ?>
</div>

<?php if (isset($_GET['status'])): ?>
    <div class="alert alert-<?php echo $_GET['status'] === 'updated' ? 'success' : 'danger'; ?>">
        <?php
        if ($_GET['status'] === 'updated') {
            echo "Showtime updated successfully!";
        } elseif (isset($_GET['message'])) {
            echo htmlspecialchars($_GET['message']);
        }
        ?>
    </div>
<?php endif; ?>
</body>
</html>
