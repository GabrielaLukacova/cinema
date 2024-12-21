<?php 
require_once "../actions/movie_calendar_logic.php"; 
require_once "../../components/views/admin_navbar.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Calendar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../admin_style/admin_style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container my-4">
    <!-- Button to Scroll to Form -->
    <div class="container my-4 text-end">
        <a href="#addShowtimeForm" class="btn btn-success btn-lg">+ New showtime</a>
    </div>

    <h2 class="text-center mb-5">Movie calendar</h2>

    <!-- Movie Showtimes Table -->
    <table class="table p-4 shadow rounded bg-white">
        <thead class="thead-dark">
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Movie title</th>
                <th>Room</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($movieShowtimes)): ?>
                <tr>
                    <td colspan="7" class="text-center">No showtimes available.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($movieShowtimes as $showtime): ?>
                    <tr>
                        <td><?= htmlspecialchars($showtime['date']); ?></td>
                        <td><?= date('H:i', strtotime($showtime['time'])); ?></td>
                        <td><?= htmlspecialchars($showtime['title']); ?></td>
                        <td><?= htmlspecialchars($showtime['room']); ?></td>
                        <td><?= htmlspecialchars($showtime['price']); ?></td>
                        <td>
                            <img src="../../../includes/media/movies/<?= htmlspecialchars($showtime['imagePath'] ?: 'default-image.png'); ?>" 
                                 alt="<?= htmlspecialchars($showtime['title']); ?>" class="movie-image">
                        </td>
                        <td>
                            <a href="edit_showtime_view.php?showTimeID=<?= htmlspecialchars($showtime['showTimeID']); ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="../actions/delete_showtime.php?showTimeID=<?= htmlspecialchars($showtime['showTimeID']); ?>" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this showtime?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Add New Showtime Form -->
    <h3 class="mt-5">Add new showtime</h3>
    <form id="addShowtimeForm" method="post" action="../actions/add_showtime.php" class="p-4 shadow rounded bg-white">
        <div class="form-group">
            <label for="movie">Select movie</label>
            <select id="movie" name="movieID" class="form-control" required>
                <option value="">Select a movie</option>
                <?php foreach ($movies as $movie): ?>
                    <option value="<?= htmlspecialchars($movie['movieID']); ?>"><?= htmlspecialchars($movie['title']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="date">Show date</label>
            <input type="date" id="date" name="date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="time">Show time</label>
            <input type="time" id="time" name="time" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="room">Room</label>
            <select id="room" name="room" class="form-control" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="text" id="price" name="price" class="form-control" placeholder="Leave empty for default (100)">
        </div>
        <button type="submit" name="submit" class="btn btn-success btn-block">+ Add showtime</button>
    </form>
</div>
</body>
</html>
