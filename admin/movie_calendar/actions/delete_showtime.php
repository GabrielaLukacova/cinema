<?php
require_once("../../../includes/connection.php");

if (isset($_GET['showTimeID'])) {
    $showTimeID = $_GET['showTimeID'];
    
    $query = $db->prepare("DELETE FROM ShowTime WHERE showTimeID = :showTimeID");
    $query->bindParam(':showTimeID', $showTimeID, PDO::PARAM_INT);
    $query->execute();

    // Redirect back to the movie calendar page with a success status
    header("Location: ../views/movie_calendar_view.php?status=deleted&showTimeID=$showTimeID");
    exit;
} else {
    // Redirect back with an error status if no showTimeID was provided
    header("Location: ../views/movie_calendar_view.php?status=0");
    exit;
}
