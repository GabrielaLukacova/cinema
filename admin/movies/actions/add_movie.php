<?php
include '../../../includes/connection.php';
include '../classes/Movie.php';

$movieHandler = new Movie($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Handle file upload
        if (isset($_FILES['movieImage']) && $_FILES['movieImage']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../../includes/media/movies/';
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
            $imagePath = null; // Default to null if no image uploaded
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
            'price' => $_POST['price'] ?? 100, // Default to 100 if not provided
            'date' => $_POST['date'] ?? date('Y-m-d'), // Default to today's date if not provided
        ];

        // Check the selected tag for limits
        $movieTag = $data['movieTag'];

        if ($movieTag === 'Hot New Movie') {
            $hotNewCount = $movieHandler->countMoviesByTag('Hot New Movie');
            if ($hotNewCount >= 8) {
                // Replace the first movie with the tag
                $firstMovie = $movieHandler->getFirstMovieByTag('Hot New Movie');
                $movieHandler->updateMovieWithImage(
                    $firstMovie['movieID'],
                    $firstMovie['title'],
                    $firstMovie['genre'],
                    $firstMovie['runtime'],
                    $firstMovie['language'],
                    $firstMovie['ageRating'],
                    $firstMovie['description'],
                    $firstMovie['imagePath'],
                    'None'
                );
            }
        } elseif ($movieTag === 'Movie of the Week') {
            $movieOfWeekCount = $movieHandler->countMoviesByTag('Movie of the Week');
            if ($movieOfWeekCount >= 1) {
                // Replace the movie with the tag
                $firstMovie = $movieHandler->getFirstMovieByTag('Movie of the Week');
                $movieHandler->updateMovieWithImage(
                    $firstMovie['movieID'],
                    $firstMovie['title'],
                    $firstMovie['genre'],
                    $firstMovie['runtime'],
                    $firstMovie['language'],
                    $firstMovie['ageRating'],
                    $firstMovie['description'],
                    $firstMovie['imagePath'],
                    'None'
                );
            }
        }

        // Add movie to the database
        if ($movieHandler->addMovie($data)) {
            header("Location: ../views/movie_list.php?success=Movie added successfully!");
            exit();
        } else {
            throw new Exception("Failed to add movie.");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        echo "<p style='color: red;'>Error: {$error}</p>";
    }
}
