<?php

session_start();

$user_id = $_SESSION['user_id'];

$mysqli = require "../database.php";

$columns = $_GET["array"];

$sql = "SELECT ut.id, ut.user_id, $columns
        FROM
        user_data ud,
        user_tracks ut
        WHERE ud.id = $user_id
        AND ut.user_id = ud.id";

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