<?php
include '../includes/dbconfig.php';
require "../includes/vendor/autoload.php";
use PhpKairos\PhpKairos;
session_start();

$api     = 'http://api.kairos.com/';
$app_id  = '345b9a6b';
$app_key = '0ee46186eb4310b5e7936385b2f32a32';
$client = new PhpKairos( $api, $app_id, $app_key );

$encodedImage = $_POST['img'];

$gallery_name = 'users';


$response = $client->recognize($encodedImage, $gallery_name);
$result   = $response->getBody()->getContents();
echo $result;
?>