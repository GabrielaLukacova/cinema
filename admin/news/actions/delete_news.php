<?php 
require_once("../../../includes/connection.php"); 

if (isset($_GET['newsID'])) {
    $newsID = $_GET['newsID'];
    
    $query = $db->prepare("DELETE FROM News WHERE newsID = :newsID");
    $query->bindParam(':newsID', $newsID, PDO::PARAM_INT);
    $query->execute();

    header("Location: ../views/news_list.php?status=deleted&newsID=$newsID");
} else {
    header("Location: ../views/news_list.php?status=0");
}
