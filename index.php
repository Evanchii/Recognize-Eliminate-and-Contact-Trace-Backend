<?php
include 'includes/dbconfig.php';
$auth = $firebase->createAuth();
session_start();

if(isset($_SESSION['uid'])) {
    header('Location: pages/dashboard.php');
}

if(isset($_POST['login'])) {
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

            $reference = $database->getReference("Users/" . $uid . "/info");
            $type = $reference->getChild('Type')->getValue();

            $_SESSION['uid'] = $uid;
            $_SESSION['token'] = $token;

            if($type == "visitor" || $type == "admin") {
                echo('<script>alert("User isn\'t permitted to use the module!");</script>');
                // header('Location: pages/logout.php');
            } else {
                $_SESSION['type'] = $type;
                $_SESSION['estName'] = $reference->getChild('name')->getValue();

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
        <title>Login | REaCT-Core</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles/public-common.css">
        <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
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
                    <input type="text" id="email" name="username" placeholder="company_username" required>
                    <p>Password</p>
                    <input type="password" id="password" name="password"placeholder="••••••••" required>
                    <p class="center"><input type="submit" name="login" value="Log in"></p>
                    <!-- TODO: Remove before production -->
                    <!-- <a onclick="debugAcct()">DEBUG: LOGIN</a> -->
                    <script>
                        function debugAcct() {
                            document.getElementById('email').value = "establishment@react-app.ga"
                            document.getElementById('password').value = "REaCT2021"
                        }
                    </script>
                    <!-- TODO: Change HREF value to front-end when uploading to web -->
                    <p>Don't have an account? <a href="https://react-app.ga/">Return to web</a></p>
                </form>

            </div>
        </div>
    </body>
</html>