<?php 
include '../includes/dbconfig.php';
date_default_timezone_set('Asia/Manila');
session_start();

if(isset($_POST["accept"])) {
    echo '<pre>';
    var_dump($_POST);
    echo '</pre>';
    $date = $_POST["date"];
    $time = $_POST["time"];
    $uid = $_SESSION["loggedUID"];
    $estName = $_SESSION["estName"];
    $timestamp = $_POST["timestamp"];

    $database->getReference('History')->update(
        [
            $date => [
                $timestamp => [
                    'time' => $time,
                    'date' => $date,
                    'uid' => $uid,
                    'estName' => $estName,
                ],
            ],
        ]
    );

    $database->getReference('Users')->update(
        [
            $uid=> [
                'history' => [
                    $date => [
                        $timestamp => $timestamp,
                    ],
                ],
            ],
        ],
    );

    $_SESSION["loggedUID"] = "";
    header('Location: faceCapturing.php');
    echo "<script>alert('Data Recorded');</script>";
} elseif(isset($_POST["decline"])) {
    $_SESSION["loggedUID"] = "";
    header('Location: faceCapturing.php');
}

$uid = $_SESSION["loggedUID"];
$reference = $database->getReference("Users/" . $uid . "/info");

// echo($reference->getChild("faceID")->getValue());

$storage = $firebase->createStorage();
$storageClient = $storage->getStorageClient();
$defaultBucket = $storage->getBucket();


$expiresAt = new DateTime('tomorrow', new DateTimeZone('Asia/Manila'));
// echo $expiresAt->getTimestamp();

$imageReference = $defaultBucket->object($reference->getChild("faceID")->getValue());
if ($imageReference -> exists()) {
    $image = $imageReference -> signedUrl($expiresAt);
}

// echo($image);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Record/Capture - REaCT-CORE</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
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

            .content {
                padding: 3% 0%;
            }

            .pfp {
                object-fit: cover;
                width: 250px;
                height: 250px;
                border-radius: 50%;
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

            .dropdown {
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

            .side-by-side {
                width: 100%;
                display: inline-grid;
                grid-template-columns: 50vw 50vw;
                align-items: center;
                justify-items: center;
            }

            .details {
                border: 1px solid black;
                width: 30vw;
                padding: 3% 0%;
            }

            th {
                text-align: left;
            }
        </style>
    </head>
    <body>
        <header>
            <img src="../assets/text-logo.png" alt="REaCT - CORE">
            <h2>Confirm Data</h2>
        </header>
        <div class="content center side-by-side">
            <div>
                <img src="
                    <?php echo $image; ?>
                    " class="pfp" alt="User Photo">
                <h2>
                    <?php echo $reference->getChild("lName")->getValue() . ", " . $reference->getChild("fName")->getValue(); ?>
                </h2>
                <h4>
                <?php 
                    echo $reference->getChild("addNo")->getValue() . ", " . $reference->getChild("addBa")->getValue() . "<br/>";
                    echo $reference->getChild("addCi")->getValue() . ", ". $reference->getChild("addPro")->getValue() . "<br/>";
                    echo $reference->getChild("addCo")->getValue() . " " . $reference->getChild("addZip")->getValue();
                ?>
                </h4>
            </div>
            <div>
                <h1>Entry</h1>
                <form action="dataConfirmation.php" method="POST">
                    <table class="details">
                        <tr>
                            <th>Establishment</th>
                            <td>
                                <?php echo $_SESSION["estName"]; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>
                                <input type="date" name="date" id="date" value="<?php echo date("Y-m-d"); ?>" readonly="readonly">
                            </td>
                        </tr>
                        <tr>
                            <th>Time</th>
                            <td>
                                <input type="time" name="time" id="time" value="<?php echo date("H:i:s");?>" readonly="readonly">
                                <input type="hidden" name="timestamp" id="timestamp">
                            </td>
                        </tr>
                    </table>
                    <div style="display: inline-flex; width: 60%; justify-content: space-around; margin: 5% 3%">
                    <script>
                        document.getElementById("timestamp").value = Date.now();
                    </script>
                        <input type="submit" value="Accept" name="accept">
                    </form>
                    <form action="dataConfirmation.php" method="POST"><input type="submit" value="Decline" name="decline"></form>
                </div>
            </div>
        </div>

        <!-- JQuery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
        <!-- jQuery Modal -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    </body>
</html>