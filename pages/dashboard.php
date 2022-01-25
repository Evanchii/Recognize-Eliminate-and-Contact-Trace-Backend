<?php
include '../includes/dbconfig.php';
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard | REaCT-Core</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../styles/private-common.css">
        <link rel="stylesheet" href="../styles/dashboard.css">
        <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
    </head>
    <body>
        <header>
            <img src="../assets/text-logo.png" alt="REaCT - CORE">
            <h2>Dashboard</h2>
        </header>
        <div class="container">
            <div class="top-container">
                <div class="greetings">
                <h1>Hello <?php echo $_SESSION['username'] ?? 'undefined';?>!</h1>
                <br>
                <h3>Today is <?php echo date("M d, Y");?></h3>
                </div>
                <div class="card">
                    <p>Visitors today</p>
                    <h3>0</h3>
                </div>
            </div>
            <h4 class="center">What would you like to do?</h4>
            <div class="cards">
                <a href="faceCapturing.php">
                    <div class="card">
                        <img src="../assets/placeholder.png" alt="Icon">
                        <p>Start Session</p>
                    </div>
                </a>
                <a href="logout.php">
                    <div class="card">
                        <img src="../assets/placeholder.png" alt="Icon">
                        <p>Log out</p>
                    </div>
                </a>
            </div>
        </div>
    </body>
</html>