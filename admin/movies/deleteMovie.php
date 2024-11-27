<?php 
require_once "../../includes/connection.php"; 
require_once "../components/admin_navbar.php"; 


if (isset($_GET['movieID'])) {
    $movieID = $_GET['movieID'];
    
    $query = $db->prepare("DELETE FROM Movie WHERE movieID = :movieID");
    $query->bindParam(':movieID', $movieID, PDO::PARAM_INT);
    $query->execute();

    header("Location: movies.php?status=deleted&movieID=$movieID");
} else {
    header("Location: movies.php?status=0");
}
