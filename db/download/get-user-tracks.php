<?php

session_start();

$current_user_id = $_SESSION['user_id'];

$mysqli = require "../database.php";

$columns = $_GET["array"];

$sql = "SELECT $columns
        FROM
        user_data ud,
        audio_metadata am
        WHERE ud.id = $current_user_id
        AND am.user_id = ud.id";

$result = mysqli_query($mysqli, $sql);

$data = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
};

echo json_encode($data);

mysqli_close($mysqli);

?>