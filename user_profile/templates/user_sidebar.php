<?php require_once 'user_sidebar_logic.php'; ?>

<!-- Sidebar HTML -->
<ul class="user-account-sidebar">
    <li class="user-nav-item">
        <a href="../views/tickets.php" class="user-account-sidebar-link <?= $currentPage === 'tickets.php' ? 'active' : ''; ?>">
            <span class="material-icons">local_activity</span> My tickets
        </a>
    </li>
    <li class="user-nav-item">
        <a href="../views/user_data.php" class="user-account-sidebar-link <?= $currentPage === 'user_data.php' ? 'active' : ''; ?>">
            <span class="material-icons">face</span> Personal data
        </a>
    </li>
    <li class="user-nav-item">
        <a href="../../loginPDO/actions/logout.php" class="user-account-sidebar-link">
            <span class="material-icons">logout</span> Log out
        </a>
    </li>
</ul>

<!-- User Profile Section -->
<section class="user-account-profile">
    <img src="<?= $userData['userPicture'] ?>" alt="Profile Picture" class="user-account-avatar">
    <h2 class="user-account-name"><?= $userData['firstName'] . ' ' . $userData['lastName']; ?></h2>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <!-- Profile Picture Upload Form -->
    <form action="" method="post" enctype="multipart/form-data">
        <label for="userPicture">Change Profile Picture:</label>
        <input type="file" name="userPicture" id="userPicture" accept="image/jpeg, image/png">
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</section>
