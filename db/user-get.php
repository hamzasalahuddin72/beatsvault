<?php

session_start();

$user_id = $_SESSION['user_id'];

$mysqli = require "./database.php";

$sql = "SELECT au.username, ud.acc_type, au.follower_count, ud.profile_pic_url 
        FROM all_users au, user_data ud
        WHERE au.id = ud.id
        AND au.id
        IN (SELECT id FROM all_users WHERE NOT id = $user_id AND NOT id IN (SELECT followed_id FROM user_follow WHERE follower_id = $user_id))";

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