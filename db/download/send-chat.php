<?php

session_start();

$message = $_POST["message"];
$to_user = $_POST["to_user"];

$response = array(
    'status' => 0,
    'message' => 'Something is wrong'
);



echo json_encode($response);

?>