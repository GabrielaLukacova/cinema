<section class="user-account-content">
    <h2>Personal Data</h2>
    <div class="user-account-personal-data">
        <div class="user-account-data-row">
            <span class="user-account-data-label">Name:</span>
            <span class="user-account-data-value"><?= htmlspecialchars($user->firstName); ?></span>
        </div>
        <div class="user-account-data-row">
            <span class="user-account-data-label">Surname:</span>
            <span class="user-account-data-value"><?= htmlspecialchars($user->lastName); ?></span>
        </div>
        <div class="user-account-data-row">
            <span class="user-account-data-label">Phone Number:</span>
            <span class="user-account-data-value"><?= htmlspecialchars($user->phoneNumber); ?></span>
        </div>
        <div class="user-account-data-row">
            <span class="user-account-data-label">Email:</span>
            <span class="user-account-data-value"><?= htmlspecialchars($user->email); ?></span>
        </div>
        <div class="user-account-data-row">
            <span class="user-account-data-label">Address:</span>
            <span class="user-account-data-value"><?= htmlspecialchars($user->street); ?></span>
        </div>
        <div class="user-account-data-row">
            <span class="user-account-data-label">City:</span>
            <span class="user-account-data-value"><?= htmlspecialchars($user->city); ?></span>
        </div>
        <button class="user-account-edit-btn" onclick="openEditModal()">Edit</button>
    </div>
</section>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <form action="./update_user_data.php" method="post">
            <h3>Edit Personal Data</h3>
            <label for="firstName">First Name:</label>
            <input type="text" name="firstName" value="<?= htmlspecialchars($user->firstName); ?>" required>
            <label for="lastName">Last Name:</label>
            <input type="text" name="lastName" value="<?= htmlspecialchars($user->lastName); ?>" required>
            <label for="phoneNumber">Phone Number:</label>
            <input type="text" name="phoneNumber" value="<?= htmlspecialchars($user->phoneNumber); ?>">
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user->email); ?>" required>
            <label for="street">Address:</label>
            <input type="text" name="street" value="<?= htmlspecialchars($user->street); ?>">
            <label for="city">City:</label>
            <input type="text" name="city" value="<?= htmlspecialchars($user->city); ?>" disabled>
            <button type="submit">Save</button>
            <button type="button" onclick="closeEditModal()">Close</button>
        </form>
    </div>
</div>
<script>
function openEditModal() {
    document.getElementById('editModal').style.display = 'block';
}
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>
