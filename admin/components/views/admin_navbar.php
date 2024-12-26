<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin navbar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../admin_style/admin_style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="d-flex">
        <nav class="sidebar" style="padding: 0;">
            <div class="p-3">
                <h4 class="text-center">Admin panel</h4>
                <ul class="nav flex-column mt-4">
                    <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
                    <li class="nav-item">
                        <a href="../../dashboard/views/dashboard.php" class="nav-link <?php echo $currentPage == 'dashboard.php' ? 'active' : ''; ?>">
                            <span class="material-icons">dashboard</span> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../cinema/views/cinema.php" class="nav-link <?php echo $currentPage == 'cinema.php' ? 'active' : ''; ?>">
                            <span class="material-icons">theaters</span> Cinema
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../movies/views/movie_list.php" class="nav-link <?php echo $currentPage == 'movies.php' ? 'active' : ''; ?>">
                            <span class="material-icons">movie</span> Movies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../movie_calendar/views/movie_calendar_view.php" class="nav-link <?php echo $currentPage == 'movie_calendar.php' ? 'active' : ''; ?>">
                            <span class="material-icons">calendar_today</span> Movie calendar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../news/views/news_list.php" class="nav-link <?php echo $currentPage == 'news.php' ? 'active' : ''; ?>">
                            <span class="material-icons">article</span> News
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../admin_loginPDO/actions/logout.php" class="nav-link <?php echo $currentPage == 'logout.php' ? 'active' : ''; ?>"> 
                        <span class="material-icons">logout</span> Logout
                    </a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="content p-4" id="main-content">
        </div>
    </div>
</body>
</html>

