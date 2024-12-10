<?php
require_once(__DIR__ . "/../loginPDO/session.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
</head>
<body>
    
</body>
</html>
    <nav class="navbar">
        <div class="logo">
            <a href="../core/home.php"><img src="../includes/media/logo/dream-screen-red.png" alt="Dream Screen Logo"></a>
        </div>
        <ul class="nav-links">
            <li><a href="../movies/views/all_movies.php">Movies</a></li>
            <li><a href="../news/all_news.php">News</a></li>
            <li>
            <?php if (logged_in()): ?>
                <!-- If logged in, show the profile page -->
                <a href="../user_profile/user_profile.php">My profile</a>
            <?php else: ?>
                <!-- If not logged in, redirect to login -->
                <a href="../loginPDO/login.php">Login</a>
            <?php endif; ?>
        </li>
        </ul>
    </nav>


