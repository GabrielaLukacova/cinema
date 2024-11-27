<?php 
require_once "../../includes/connection.php"; 
require_once "../components/admin_navbar.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie calendar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../admin_style/admin_style.css?v=1.2">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    







<?php
// Fetch all movies with their showtimes
try {
    $query = $db->prepare("
        SELECT ShowTime.showTimeID, Movie.title, ShowTime.date, ShowTime.time, ShowTime.room, ShowTime.price, Movie.imagePath
        FROM ShowTime
        JOIN Movie ON ShowTime.movieID = Movie.movieID
        ORDER BY ShowTime.date ASC, ShowTime.time ASC
    ");
    $query->execute();
    $movieShowtimes = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Fetch all movies for the dropdown
$queryMovies = $db->prepare("SELECT movieID, title FROM Movie");
$queryMovies->execute();
$movies = $queryMovies->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movie Calendar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../includes/admin_style.css">
    <style>
        .movie-image {
            width: 80px; /* Set a fixed width for images */
            height: auto; /* Maintain aspect ratio */
        }
        .table th, .table td {
            vertical-align: middle; /* Center align content vertically */
        }
        .table img {
            border-radius: 5px; /* Slightly round the corners of images */
        }
    </style>
</head>




<body>
<div class="container my-4">
  
<!-- Button to Scroll to Form -->
<div class="container my-4 text-end">
    <a href="#addShowtimeForm" class="btn btn-success btn-lg">+ New Showtime</a>
</div>

    <h2 class="text-center mb-5">Movie Calendar</h2>

    <!-- Movie Showtimes Table -->
    <table class="table p-4 shadow rounded bg-white">
        <thead class="thead-dark">
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Movie Title</th>
                <th>Room</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($movieShowtimes)): ?>
                <tr>
                    <td colspan="6" class="text-center">No showtimes available.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($movieShowtimes as $showtime): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($showtime['date']); ?></td>
                        <td><?php echo date('H:i', strtotime($showtime['time'])); ?></td>
                        <td><?php echo htmlspecialchars($showtime['title']); ?></td>
                        <td><?php echo htmlspecialchars($showtime['room']); ?></td>
                        <td><?php echo htmlspecialchars($showtime['price']); ?></td>
                        <td>
                            <?php if (!empty($showtime['imagePath'])): ?>
                                <img src="../../includes/media/movies/<?php echo htmlspecialchars($showtime['imagePath']); ?>" alt="<?php echo htmlspecialchars($showtime['title']); ?>" class="movie-image">
                            <?php else: ?>
                                <img src="../../includes/media/movies/default-image.png" alt="No Image" class="movie-image"> <!-- Fallback image -->
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_showtime.php?showTimeID=<?php echo $showtime['showTimeID']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="delete_showtime.php?showTimeID=<?php echo $showtime['showTimeID']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this showtime?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

   <!-- Form to Add New Showtime -->
<h3 class="mt-5">Add New Showtime</h3>
<form id="addShowtimeForm" method="post" action="add_showtime.php" class="p-4 shadow rounded bg-white">
    <div class="form-group">
        <label for="movie">Select Movie</label>
        <select id="movie" name="movieID" class="form-control" required>
            <option value="">Select a movie</option>
            <?php foreach ($movies as $movie): ?>
                <option value="<?php echo $movie['movieID']; ?>"><?php echo htmlspecialchars($movie['title']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="date">Show Date</label>
        <input type="date" id="date" name="date" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="time">Show Time</label>
        <input type="time" id="time" name="time" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="room">Room</label>
        <input type="text" id="room" name="room" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="room">Price</label>
        <input type="text" id="price" name="price" class="form-control" required>
    </div>
    <button type="submit" name="submit" class="btn btn-success btn-block">
       + Add Showtime
    </button>
</form>


    <?php
    // Handle form submission for adding new showtime
    if (isset($_POST['submit'])) {
        $movieID = $_POST['movieID'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $room = $_POST['room'];

        // Insert showtime data into the database
        $insertQuery = $db->prepare("INSERT INTO ShowTime (movieID, date, time, room) 
                                       VALUES (:movieID, :date, :time, :room)");
        $executeResult = $insertQuery->execute([
            ':movieID' => $movieID,
            ':date' => $date,
            ':time' => $time,
            ':room' => $room
        ]);

        if ($executeResult) {
            header("Location: movie_calendar.php?status=added");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Error adding showtime: ";
            print_r($insertQuery->errorInfo());
            echo "</div>";
        }
    }
    ?>
</div>

<?php if (isset($_GET['status'])): ?>
    <div class="alert alert-<?php echo $_GET['status'] === 'added' ? 'success' : 'danger'; ?>">
        <?php
        if ($_GET['status'] === 'added') {
            echo "Showtime added successfully!";
        } elseif (isset($_GET['message'])) {
            echo htmlspecialchars($_GET['message']);
        }
        ?>
    </div>
<?php endif; ?>
</body>
</html>
