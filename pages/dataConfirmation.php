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

    $database->getReference('History/'.$date)->update(
        [
            $timestamp => [
                'time' => $time,
                'date' => $date,
                'uid' => $uid,
                'estName' => $estName,
            ],
        ]
    );

    $database->getReference('Users/'.$uid.'/history/'.$date)->update(
        [
            $timestamp => $timestamp,
        ],
    );

    // TO-DO: Establishment History Key

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
    <title>Entry Review | REaCT-Core</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <link rel="stylesheet" href="../styles/private-common.css">
    <link rel="stylesheet" href="../styles/dataConfirmation.css">
    <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
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
                            <input type="date" name="date" id="date" value="<?php echo date("Y-m-d"); ?>"
                                readonly="readonly">
                        </td>
                    </tr>
                    <tr>
                        <th>Time</th>
                        <td>
                            <input type="time" name="time" id="time" value="<?php echo date("H:i:s");?>"
                                readonly="readonly">
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
            <form action="dataConfirmation.php" method="POST"><input type="submit" value="Decline" name="decline">
            </form>
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