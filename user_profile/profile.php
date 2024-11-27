<?php
require_once "../includes/connection.php";
require_once "../navbar_footer/cinema_navbar.php";


// Fetching from db
$query = $db->prepare("
    SELECT 
        u.*, 
        pc.city
    FROM User u
    LEFT JOIN postalCode pc ON u.postalCode = pc.postalCode
");
$query->execute();






?>
<a href="../loginPDO/logout.php" class="btn btn-danger">Logout</a>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

    <div class="user-account-container">

        <!-- User Info Section -->
        <div class="user-account-profile">
            <div class="user-account-info">
                <img src="user-placeholder.png" alt="User Avatar" class="user-account-avatar">
                <p class="user-account-name">Alex Sebtorn</p>
            </div>
        </div>

        <!-- Main Content Section -->
        <div class="user-account-main">
            <!-- Sidebar -->
            <aside class="user-account-sidebar">
                <a href="#" class="user-account-sidebar-link">My Tickets</a>
                <a href="#" class="user-account-sidebar-link active">Personal Data</a>
                <a href="../loginPDO/logout.php" class="user-account-sidebar-link">Log Out</a>
            </aside>

            <!-- Personal Data Section -->
            <section class="user-account-content">
                <div class="user-account-personal-data">
                    <div class="user-account-data-row">
                        <span class="user-account-data-label">Name</span>
                        <span class="user-account-data-value">Alex</span>
                    </div>
                    <div class="user-account-data-row">
                        <span class="user-account-data-label">Surname</span>
                        <span class="user-account-data-value">Sebtorn</span>
                    </div>
                    <div class="user-account-data-row">
                        <span class="user-account-data-label">Phone Number</span>
                        <span class="user-account-data-value">+45 00 90 80</span>
                    </div>
                    <div class="user-account-data-row">
                        <span class="user-account-data-label">Email</span>
                        <span class="user-account-data-value">Alex99@gmail.com</span>
                    </div>
                    <div class="user-account-data-row">
                        <span class="user-account-data-label">Address</span>
                        <span class="user-account-data-value">Yellow 56</span>
                    </div>
                    <div class="user-account-data-row">
                        <span class="user-account-data-label">City</span>
                        <span class="user-account-data-value">Esbjerg</span>
                    </div>
                    <div class="user-account-data-row">
                        <span class="user-account-data-label">Postal Code</span>
                        <span class="user-account-data-value">6700</span>
                    </div>
                    <button class="user-account-edit-btn">Edit</button>
                </div>
            </section>
        </div>
    </div>
