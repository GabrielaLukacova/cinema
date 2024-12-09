<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session only if it hasn't started yet.
}require_once '../classes/user.php';
require_once '../templates/header.php';
require_once '../templates/user_sidebar.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$userID = $_SESSION['user_id'];
$user = new User();
$userData = $user->getUserProfile($userID);
?>

<div class="user-account-main">
    <div class="edit-profile-container">
        <form action="../actions/edit_user_action.php" method="POST" enctype="multipart/form-data" class="edit-profile-form">
            <h2>Edit My Profile</h2>

            <!-- Input Fields -->
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" value="<?= htmlspecialchars($userData['firstName']); ?>" required>
            </div>

            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" value="<?= htmlspecialchars($userData['lastName']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="phoneNumber">Phone Number:</label>
                <input type="text" id="phoneNumber" name="phoneNumber" value="<?= htmlspecialchars($userData['phoneNumber']); ?>">
            </div>

            <div class="form-group">
                <label for="street">Street:</label>
                <input type="text" id="street" name="street" value="<?= htmlspecialchars($userData['street']); ?>">
            </div>

            <div class="form-group">
                <label for="postalCode">Postal Code:</label>
                <input type="text" id="postalCode" name="postalCode" value="<?= htmlspecialchars($userData['postalCode']); ?>">
            </div>

            <div class="form-group">
                <label for="userPicture">Profile Picture:</label>
                <input type="file" id="userPicture" name="userPicture" accept="image/jpeg, image/png">
            </div>

            <!-- Buttons -->
            <div class="form-actions">
                <button type="submit" class="btn-primary">Save Changes</button>
                <a href="user_data.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../templates/footer.php'; ?>
