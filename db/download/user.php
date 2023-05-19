<?php

session_start();

$user_name_profile = $_GET["user"];
$user_id = $_SESSION['user_id'];

$columns = '    
                username, 
                firstname, 
                lastname, 
                email,
                public
                ';

$mysqli = require "../database.php";

$sql1 = "SELECT ud.*, $columns FROM all_users au, user_data ud WHERE au.username = '$user_name_profile' AND au.id = ud.id LIMIT 1";

$sql2 = "SELECT followed_id FROM user_follow WHERE follower_id = $user_id AND followed_id = (SELECT id FROM all_users WHERE username = '$user_name_profile')";

$sql3 = "SELECT au.username, ud.profile_pic_url, ut.*
        FROM all_users au, user_data ud, user_tracks ut
        WHERE (au.id IN (SELECT followed_id FROM user_follow WHERE follower_id = $user_id AND followed_id = (SELECT id FROM all_users WHERE username = '$user_name_profile')) OR (au.username = '$user_name_profile' AND au.public = 1) OR (au.username = '$user_name_profile' AND au.id = $user_id))
        AND ud.id = au.id
        AND ut.user_id = ud.id";

$result1 = mysqli_query($mysqli, $sql1);
$result2 = mysqli_query($mysqli, $sql2);
$result3 = mysqli_query($mysqli, $sql3);

$selUser = array();
$selUserTracks = array();

if (mysqli_num_rows($result1) > 0) {
    while ($row = mysqli_fetch_assoc($result1)) {
        $selUser[] = $row;
        if ($row["id"] == $user_id) {
            $selUser[] = "current_user";
        } else {
            if (mysqli_num_rows($result2) > 0) {
                $selUser[] = "is_followed";
            }
        }
        if (mysqli_num_rows($result3) > 0) {
            while ($row = mysqli_fetch_assoc($result3)) {
                $selUserTracks[] = $row;
            }
        }
    }
}


$data = [$selUser, $selUserTracks];

echo json_encode($data);

mysqli_close($mysqli);

?>