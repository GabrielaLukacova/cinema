<?php
include '../../includes/connection.php';
include 'classes/Movie.php';

$movieHandler = new Movie($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Handle file upload
        if (isset($_FILES['movieImage']) && $_FILES['movieImage']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../includes/media/movies/';
            $fileName = basename($_FILES['movieImage']['name']);
            $targetFilePath = $uploadDir . $fileName;

            // Validate file type
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($fileType), $allowedTypes)) {
                throw new Exception("Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.");
            }

            // Move the uploaded file to the target directory
            if (!move_uploaded_file($_FILES['movieImage']['tmp_name'], $targetFilePath)) {
                throw new Exception("Failed to upload the image.");
            }

            // Store the relative path in the database
            $imagePath = $fileName;
        } else {
            $imagePath = null; // Or set a default image path
        }

        // Prepare data for the movie
        $data = [
            'title' => $_POST['title'],
            'genre' => $_POST['genre'],
            'runtime' => $_POST['runtime'],
            'language' => $_POST['language'],
            'ageRating' => $_POST['ageRating'] ?? null,
            'description' => $_POST['description'] ?? null,
            'imagePath' => $imagePath,
            'movieTag' => $_POST['movieTag'] ?? 'None',
        ];

        // Check the selected tag for limits
        $movieTag = $data['movieTag'];

        if ($movieTag === 'Hot New Movie') {
            $hotNewCount = $movieHandler->countMoviesByTag('Hot New Movie');

            if ($hotNewCount >= 8) {
                $firstMovie = $movieHandler->getFirstMovieByTag('Hot New Movie');
                echo "<script>
                    if (confirm('The limit of 8 movies for Hot New Movie is reached. Do you want to replace \"{$firstMovie['title']}\"?')) {
                        window.location.href = 'replace_movie.php?movieID={$firstMovie['movieID']}&newTag=Hot New Movie';
                    } else {
                        window.history.back();
                    }
                </script>";
                exit;
            }
        } elseif ($movieTag === 'Movie of the Week') {
            $movieOfWeekCount = $movieHandler->countMoviesByTag('Movie of the Week');

            if ($movieOfWeekCount >= 1) {
                $firstMovie = $movieHandler->getFirstMovieByTag('Movie of the Week');
                echo "<script>
                    if (confirm('The Movie of the Week tag is already assigned to \"{$firstMovie['title']}\". Do you want to replace it?')) {
                        window.location.href = 'replace_movie.php?movieID={$firstMovie['movieID']}&newTag=Movie of the Week';
                    } else {
                        window.history.back();
                    }
                </script>";
                exit;
            }
        }

        // Add movie to the database
        if ($movieHandler->addMovie($data)) {
            header("Location: movie_list.php?success=Movie added successfully!");
            exit();
        } else {
            throw new Exception("Failed to add movie.");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        echo "<p style='color: red;'>Error: {$error}</p>";
    }
}
