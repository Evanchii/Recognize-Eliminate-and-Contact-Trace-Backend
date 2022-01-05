<?php 
include '../includes/dbconfig.php';
session_start();

$uid = $_POST['uid'];

$check = $database->getReference("Users/" . $uid . "/info/Type")->getValue();
if(empty($check)) {
    echo false;
} else {
    echo true;
    $_SESSION["loggedUID"] = $uid;
}
?>