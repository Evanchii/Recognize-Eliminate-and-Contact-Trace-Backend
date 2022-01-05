<?php
include 'includes/dbconfig.php';
$auth = $firebase->createAuth();
session_start();

if(isset($_SESSION['uid'])) {
    header('Location: pages/dashboard.php');
}

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(!(str_contains($username, '@') && str_contains($username, '.'))) {
        $_SESSION['username'] = $username;
        $username .= "@core.react.ga";
    } else {
        $pieces = explode("@", $username);
        $_SESSION['username'] = $pieces[0];
    }

    try {
        $signInResult = $auth->signInWithEmailAndPassword($username, $password);
        $token = $signInResult->idToken();
        try {
            $verIdToken = $auth->verifyIdToken($token);
            $uid = $verIdToken->claims()->get('sub');

            $reference = $database->getReference("Users/" . $uid . "/info/Type");
            $type = $reference->getValue();

            if($type == "User" || $type == "LGU") {
                echo('<script>alert("User isn\'t permitted to use the module!");</script>');
            } else {
                $_SESSION['uid'] = $uid;
                $_SESSION['token'] = $token;
                $_SESSION['type'] = $type;

                header('Location: pages/dashboard.php');
            }
        } catch (InvalidToken $e) {
            echo '<script>alert("The token is invalid!")</script>';
        } catch (\InvalidArgumentException $e) {
            echo '<script>alert("The token could not be parsed!")</script>';
        }
    } catch (Exception $e) {
        echo '<script>alert("Invalid Email and/or Password!")</script>';
    }


}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login - REaCT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Asap:wght@400;500&family=Quicksand:wght@400;500&display=swap');
            
            * {
                font-family: 'Asap', sans-serif;
                font-family: 'Quicksand', sans-serif;
            }

            body {
                background-image: radial-gradient(#b5b5b5 10%, transparent 0%);
                background-color: #e0e0e0;
                background-position: 0 0, 50px 50px;
                background-size: 20px 20px;
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
                margin: 20vh auto;
                padding: 3% 1%;
                box-shadow: 0px 0px 8px 0px rgba(0,0,0,0.75);
                -webkit-box-shadow: 0px 0px 8px 0px rgba(0,0,0,0.75);
                -moz-box-shadow: 0px 0px 8px 0px rgba(0,0,0,0.75);
                display: grid;
                grid-template-columns: 50% 50%;
                justify-items: stretch;
                align-items: center;
                justify-content: space-evenly;
                border-radius: 15px;
            }

            .left {
                padding: 5%;
            }

            .left>img {
                width: 75%;
            }

            .right {
                padding: 3%;
                display: flex;
                flex-wrap: nowrap;
                flex-direction: column;
                align-items: center;
            }

            form>input {
                width: 100%;
            }

            .right>img {
                width: 50%;
            }

            a {
                text-decoration: none;
            }

            @media (width: 480px), (orientation: portrait) {
                .container {
                    width: 75vw;
                    margin: 15vh auto;
                    display: block;
                }
                
                .left>img {
                    width: 20vw;
                }
            }
        </style>
    </head>
    <body>
    <div class="container">
            <div class="left center">
                <img src="assets/logo.png" alt="REaCT Logo">
            </div>
            <div class="right">
                <img src="assets/text-logo.png" alt="REaCT Login">
                <form action="index.php" method="POST">
                    <p>Username</p>
                    <input type="text" name="username" placeholder="company_username" required>
                    <p>Password</p>
                    <input type="password" name="password"placeholder="••••••••" required>
                    <p class="center"><input type="submit" name="inSubmit" value="Log in"></p>
                    <a href="pages/dashboard.php">DEBUG: LOGIN</a>
                    <!-- TODO: Change HREF value to front-end when uploading to web -->
                    <p>Don't have an account? <a href="../REaCT-Web/">Return to web</a></p>
                </form>

            </div>
        </div>
    </body>
</html>