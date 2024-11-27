<?php 
require_once("../includes/connection.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $query = $db->query("SELECT newsID, title, category, imagePath FROM News ORDER BY newsID DESC");
    $getNews = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching news: " . $e->getMessage();
    $getNews = [];
}