<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
  $query = $db->prepare("SELECT phoneNumber, email FROM Cinema LIMIT 1");
  $query->execute();
    $cinema = $query->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching phone number and email: " . $e->getMessage();
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Footer</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
   
<footer class="footer">
  <div class="logo">
    <a href="../../core/views/home.php"><img src="../../includes/media/logo/dream-screen-red.png" alt="Dream Screen Logo"></a>
  </div>
  <div class="contact-info">
    <p><i class="fas fa-phone"></i><?php echo htmlspecialchars($cinema['phoneNumber']); ?></p>
    <p><i class="fas fa-envelope"></i><?php echo htmlspecialchars($cinema['email']);?></p>
  </div>
  <p>&copy; 2024 Dream Screen. All rights reserved.</p>
</footer>
</body>
</html>



