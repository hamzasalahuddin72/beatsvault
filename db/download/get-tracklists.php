<?php

session_start();

$current_user_id = $_SESSION['user_id'];

$mysqli = require "../database.php";

$sql = "SELECT af.file_url, au.username, ud.profile_pic_url, am.title, am.genre,
        ud.profile_pic_url, ud.profile_cover_url, cf.cover_url
        FROM
        all_users au,
        user_data ud, 
        audio_files af,
        audio_metadata am,
        cover_files cf
        tracklists t
        WHERE 
        au.id = $current_user_id
        AND ud.id = au.id
        AND ud.id = af.user_id
        AND af.id = am.id 
        AND am.id = cf.id
        AND af.id = t.user_tracks
        ";

$result = mysqli_query($mysqli, $sql);

$data = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
};

echo json_encode($data);

?>