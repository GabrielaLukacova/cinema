<?php 
require_once("../../includes/connection.php"); 
include '../includes/admin_navbar.php'; 

if (isset($_GET['newsID'])) {
    $newsID = $_GET['newsID'];
    
    $query = $db->prepare("DELETE FROM News WHERE newsID = :newsID");
    $query->bindParam(':newsID', $newsID, PDO::PARAM_INT);
    $query->execute();

    header("Location: news.php?status=deleted&newsID=$newsID");
} else {
    header("Location: news.php?status=0");
}
