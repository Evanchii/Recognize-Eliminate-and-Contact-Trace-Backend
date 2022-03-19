<?php
include '../includes/dbconfig.php';
session_start();

$infoRef = $database->getReference('Users/'.$_SESSION['uid'].'/info');

$main = $_SESSION['type'] == 'establishment' ? $_SESSION['uid'] : $infoRef->getChild('main')->getValue();
$hisRef = $database->getReference('Users/'.$main.'/history')->getSnapshot();

// var_dump($_SESSION);
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
                    <h3><?php 
                    if($hisRef->getChild(date('Y-m-d'))->hasChildren()) {
                        echo $hisRef->getChild(date('Y-m-d'))->numChildren();
                    } else {
                        echo '0';
                    }
                   ?></h3>
                </div>
            </div>
            <h4 class="center">What would you like to do?</h4>
            <div class="cards">
                <a href="faceCapturing.php">
                    <div class="card">
                        <i class="fa-solid fa-play icon"></i>
                        <p>Start Session</p>
                    </div>
                </a>
                <a href="logout.php">
                    <div class="card">
                        <i class="fa-solid fa-arrow-right-from-bracket icon"></i>
                        <p>Log out</p>
                    </div>
                </a>
            </div>
        </div>
    </body>

<!-- FontAwesome -->
  <script src="https://kit.fontawesome.com/a2501cd80b.js" crossorigin="anonymous"></script>

  <!-- JQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
  <!-- jQuery Modal -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

</html>