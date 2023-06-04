<?php

session_start();

$to_user = $_GET["to_user"];

$mysqli = require "../database.php";

$sql = "SELECT * FROM user_chat
        WHERE ((from_user = '$to_user' AND to_user = {$_SESSION['user_id']})
        OR (to_user = '$to_user' AND from_user = {$_SESSION['user_id']}))";

$result = mysqli_query($mysqli, $sql);

$chat = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $chat[] = $row;
    }
}

$data = array(0=>array_values(($chat)), 1=>$_SESSION["user_id"]);

echo json_encode($data);

mysqli_close($mysqli);

?>