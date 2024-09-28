<?php

session_start();

$current_user_id = $_SESSION['user_id'];

$mysqli = require "../database.php";

$columns = $_GET["array"];

$sql = "SELECT $columns FROM all_users au, user_data ud, audio_metadata am
        WHERE (au.id = $current_user_id
        OR au.id IN (SELECT followed_id FROM user_follow WHERE follower_id = $current_user_id))
        AND ud.id = au.id
        AND am.user_id = ud.id
        AND ud.id = am.user_id";

$result = mysqli_query($mysqli, $sql);

$data = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}
;

echo json_encode($data);

mysqli_close($mysqli);

?>