<?php

session_start();

$user_id = $_SESSION['user_id'];

$mysqli = require "../database.php";

$columns = $_GET["array"];

$sql = "SELECT username, ut.*, ud.* FROM all_users au, user_data ud, user_tracks ut
        WHERE (au.id = $user_id
        OR au.id IN (SELECT followed_id FROM user_follow WHERE follower_id = $user_id))
        AND ud.id = au.id
        AND ut.user_id = ud.id
        AND ud.id = ut.user_id";

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