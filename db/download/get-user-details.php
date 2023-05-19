<?php

session_start();

$user_id = $_SESSION["user_id"];

$response = array(
    'status' => 0,
    'message' => 'Something is wrong',
);

$mysqli = require "../database.php";

$sql = "SELECT username, firstname, lastname, email, public, user_data.*
        FROM all_users, user_data
        WHERE all_users.id = $user_id
        AND user_data.id = $user_id";

$result = $mysqli->query($sql);

$data = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

echo json_encode($data);

mysqli_close($mysqli);

?>