<?php 
require_once "../../includes/connection.php"; 
require_once "../components/admin_navbar.php"; 



// cinemaID from URL
$cinemaID = $_GET['cinemaID'] ?? null;
if ($cinemaID) {
    // Fetching from db
    $query = $db->prepare("SELECT * FROM Cinema WHERE cinemaID = :cinemaID");
    $query->execute([':cinemaID' => $cinemaID]);
    $cinema = $query->fetch(PDO::FETCH_ASSOC);

    if (!$cinema) {
        echo "<div class='alert alert-danger'>Cinema not found.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>Cinema ID not provided.</div>";
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $email = trim($_POST['email']);
    $street = trim($_POST['street']);
    $postalCode = trim($_POST['postalCode']);
    $description = trim($_POST['description']);

    // Update db
    $updateQuery = $db->prepare("
        UPDATE Cinema 
        SET name = :name, phoneNumber = :phoneNumber, email = :email, 
            street = :street, postalCode = :postalCode, description = :description
        WHERE cinemaID = :cinemaID
    ");

    $updateQuery->execute([
        ':name' => $name,
        ':phoneNumber' => $phoneNumber,
        ':email' => $email,
        ':street' => $street,
        ':postalCode' => $postalCode,
        ':description' => $description,
        ':cinemaID' => $cinemaID
    ]);

    // back to cinema page
    header("Location: cinema.php?status=updated");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Cinema Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../admin_style/admin_style.css?v=1.2">

</head>
<body>
<div class="container my-4">
    <h3 class="mt-5">Edit cinema details</h3>

    <form method="post" action="editCinema.php?cinemaID=<?php echo $cinemaID; ?>" class="p-4 shadow rounded bg-white">
        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($cinema['name']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="phoneNumber">Phone number</label>
            <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" value="<?php echo htmlspecialchars($cinema['phoneNumber']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($cinema['email']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="street">Street</label>
            <input type="text" id="street" name="street" class="form-control" value="<?php echo htmlspecialchars($cinema['street']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="postalCode">Postal code</label>
            <input type="text" id="postalCode" name="postalCode" class="form-control" value="<?php echo htmlspecialchars($cinema['postalCode']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" required><?php echo htmlspecialchars($cinema['description']); ?></textarea>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success">Update</button>
        </div>
    </form>
</div>
</body>
</html>



