<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="payment-container">
    <h2>Payment</h2>
    <p>Your seats have been successfully reserved!</p>
    <p>Total Price: $<?= number_format($totalPrice, 2) ?></p>
    <button onclick="window.close()">Close</button>
</div>
</body>
</html>
