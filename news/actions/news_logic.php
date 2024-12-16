<?php 
require_once("../../includes/connection.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $query = $db->query("SELECT newsID, title, category, imagePath FROM News ORDER BY newsID DESC");
    $getNews = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching news: " . $e->getMessage();
    $getNews = [];
}

// Function to get news by ID
function getNewsById($newsID) {
    global $db;

    if (!isset($newsID) || !is_numeric($newsID)) {
        return null; // Invalid news ID
    }

    $newsID = intval($newsID);
    $query = $db->prepare("SELECT * FROM News WHERE newsID = :newsID");
    $query->execute([':newsID' => $newsID]);
    return $query->fetch();
}

?>