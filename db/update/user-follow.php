<?php

session_start();

$action = $_POST["action"];
$followerId = $_SESSION["user_id"];
$followedId = $_POST["followed_id"];

$response = array(
    'status' => 0,
    'message' => 'Something is wrong'
);

$mysqli = require "../database.php";



echo json_encode($response);

?>