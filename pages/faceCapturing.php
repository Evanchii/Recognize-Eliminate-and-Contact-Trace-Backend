<?php 
include '../includes/dbconfig.php';
session_start();

if(isset($_POST["faceInput"])) {
    #Upload Face Photo
    $img = $_POST['faceInput'];
    $folderPath = "Face/";
    
    $image_parts = explode(";base64,", $img);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    
    $image_base64 = base64_decode($image_parts[1]);
    $fileName = 'sample.png'; //INSERT UID HERE
    
    $file = $folderPath . $fileName;
    file_put_contents($file, $image_base64);

    echo '<script>alert("Saved! Facial Recognition is not yet available. Please utilize QR code.")</script>';
    echo '<script>
    window.onload = function(){
        window.open("Face/sample.png", "_blank"); // will open new tab on window.onload
   }
   </script>';

}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Capture | REaCT-Core</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
        <link rel="stylesheet" href="../styles/private-common.css">
        <link rel="stylesheet" href="../styles/faceCapturing.css">
        <link rel="shortcut icon" href="../assets/favico.ico" type="image/x-icon">
    </head>
    <body>
        <header>
            <img src="../assets/text-logo.png" alt="REaCT - CORE">
            <div class="dropdown">
                <h2 class="dropbtn">Capturing&#x25BC;</h2>
                <div class="dropdown-content">
                    <a href="#settings"  rel="modal:open">Settings</a>
                    <a href="dashboard.php">End Session</a>
                </div>
            </div>
        </header>
        <div class="content center">
            <h1>Welcome to</h1>
            <h2><?php 
                // get establisment name
                if(isset($_SESSION['estName'])) {
                    echo $_SESSION['estName'];
                }
                else {
                    echo "undefined";
                }
            ?></h2>
            <div class="center">
                <form action="verify.php" method="POST" name="frmCode" id="frmCode">
                    <input type="hidden" name="QRCode" id="QRCode">
                </form>
                <!-- camera -->
                
                <form action="faceCapturing.php" method="POST" name="faceCapture" id="faceCapture">
                    <video id="Data_preview"></video>
                    <input type="hidden" name="faceInput" id="faceInput">
                </form>
                <script>
                    function getCookie(cname) {
                        let name = cname + "=";
                        let ca = document.cookie.split(';');
                        for(let i = 0; i < ca.length; i++) {
                            let c = ca[i];
                            while (c.charAt(0) == ' ') {
                            c = c.substring(1);
                            }
                            if (c.indexOf(name) == 0) {
                            return c.substring(name.length, c.length);
                            }
                        }
                        return "";
                    }

                    let camera = getCookie("camera");
                    if(camera == "") {
                        document.cookie = "camera=0; expires=Thu, 01 Jan 2038 00:00:00 UTC; path=/;";
                        camera = 0;
                    }

                    let scanner = new Instascan.Scanner({ video: document.getElementById('Data_preview')});
                    scanner.addListener('scan', function (qr_code) {
                        console.log('pass start');
                        console.log(qr_code);
                        document.getElementById("QRCode").value = qr_code;
                        console.log('pass 1');
                        console.log('pass 2');
                        $.ajax({
                            type: "POST",
                            url: "verify.php",
                            data: {
                                uid : qr_code
                            },
                            success: function (data) {
                                console.log("Data: "+ data);
                                console.log('pass 3');
                                if(data) {
                                    console.log('pass true');
                                    window.location.href = "dataConfirmation.php"
                                } else {
                                    console.log('pass false');
                                    alert("User not found! Please check if QR Code is valid\nData: "+qr_code);
                                }
                            },
                            error: function (data) {
                                console.log('pass error');
                                console.log('An error occurred.');
                                console.log(data);
                            },
                        });
                        console.log('pass end');
                        
                    });
                    Instascan.Camera.getCameras().then(function (cameras) {
                        if (cameras.length > 0) {
                        scanner.start(cameras[camera]);
                        } else {
                        console.error('Sorry, No cameras found.');
                        }
                    }).catch(function (e) {
                        console.error(e);
                    });
                </script>
                <button id="capture" onclick="capturePhoto()"> Capture</button>
                <canvas id="canvas" style="display: none;"></canvas>
                <script>
                    var video = document.querySelector("#Data_preview");
                    var canvas = document.querySelector("#canvas");
                    var faceInput = document.querySelector("#faceInput");
                    var capture = document.querySelector("#capture");
                    
                    function capturePhoto() {
                        capture.disabled = true;
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        canvas.getContext("2d").drawImage(video, 0, 0);
                        faceInput.value = canvas.toDataURL("image/webp");

                        document.getElementById("faceCapture").submit();
                    }
                </script>
            </div>
            <h3>Instruction</h3>
            <p>You may submit a photo of yourself or present your personal QR code from the Application.</p>
            <p>Once you are ready press the capture button.</p>
        </div>

        <div id="settings" class="modal">
            <h1>Settings</h1>
            <script>
                navigator.mediaDevices.enumerateDevices().then(function (devices) {
                        for(var i = 0; i < devices.length; i ++){
                            var device = devices[i];
                            if (device.kind === 'videoinput') {
                                var option = document.createElement('option');
                                option.value = device.deviceId;
                                option.text = device.label || 'camera ' + (i + 1);
                                document.querySelector('select#videoSource').appendChild(option);
                            }
                        };
                    });
            </script>
            <h3>Camera</h3>
            <select id="videoSource" onchange="changeSettings()"></select>
        </div>

        <!-- JQuery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
        <!-- jQuery Modal -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
        <script>
            function changeSettings() {
                scanner.stop();
                document.cookie = "camera="+document.getElementById("videoSource").selectedIndex+"; expires=01 Jan 2038 00:00:00 UTC; path=/;";
                Instascan.Camera.getCameras().then(function (cameras) {
                                if (cameras.length > 0) {
                                    scanner.start(cameras[document.getElementById("videoSource").selectedIndex]);
                                } else {
                                console.error('Sorry, No cameras found.');
                                }
                            }).catch(function (e) {
                                console.error(e);
                            });
            }
        </script>
    </body>
</html>