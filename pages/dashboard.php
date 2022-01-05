<?php
include '../includes/dbconfig.php';
session_start();

$_SESSION["estName"] = "SyntAxie HQ";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Dashnoard - REaCT-CORE</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Asap:wght@400;500&family=Quicksand:wght@400;500&display=swap');
            
            *,  *:before, *:after {
                font-family: 'Asap', sans-serif;
                font-family: 'Quicksand', sans-serif;
                margin: 0;
                padding: 0;
                box-sizing:border-box;
                -moz-box-sizing:border-box;
            }

            body {
                background-image: radial-gradient(#b5b5b5 10%, transparent 0%);
                background-color: #e0e0e0;
                background-position: 0 0, 50px 50px;
                background-size: 20px 20px;
            }

            body::after {
                content: "";
                background-image: url("../assets/logo.png");
                background-size: 30%;
                background-repeat: no-repeat;
                background-position: -5% bottom;
                opacity: 0.5;
                top: 0;
                left: 0;
                bottom: 0;
                right: 0;
                position: absolute;
                z-index: -1;   
            }

            .center {
                text-align: center;
            }

            .end {
                text-align: right;
            }

            .container {
                background: white;
                width: 50vw;
                height: auto;
                margin: 15vh auto;
                padding: 3% 5%;
                box-shadow: 0px 0px 8px 0px rgba(0,0,0,0.75);
                -webkit-box-shadow: 0px 0px 8px 0px rgba(0,0,0,0.75);
                -moz-box-shadow: 0px 0px 8px 0px rgba(0,0,0,0.75);
                justify-items: stretch;
                align-items: center;
                border-radius: 15px;
            }

            header {
                color: white;
                display: flex;
                justify-content: space-between;
                background: #0C112D;
                padding: 0.5% 2%;
                align-items: center;
            }

            header img {
                width: 15vw;
                filter: invert(100%) sepia(0%) saturate(0%) hue-rotate(91deg) brightness(104%) contrast(104%);
            }

            header h2 {
                background: #566cd1;
                padding: 0.5%;
                border-radius: 20px;
            }

            .top-container {
                display: flex;
                justify-content: space-between;
                padding-bottom: 5%;
            }

            .cards {
                display: flex;
                justify-content: space-evenly;
                padding-top: 5%;
            }

            .card {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: center;
                width: 10vw;
                background: white;
                padding: 3% 1%;
                box-shadow: 0px 0px 8px 0px rgba(0,0,0,0.75);
                -webkit-box-shadow: 0px 0px 8px 0px rgba(0,0,0,0.75);
                -moz-box-shadow: 0px 0px 8px 0px rgba(0,0,0,0.75);
            }

            .card img {
                border-radius: 50%;
                width: 85%;
                padding: 5%;
            }
        </style>
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