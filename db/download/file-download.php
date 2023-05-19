<?php

session_start();

$audioFileId = $_POST["id"];

$mysqli = require "../database.php";

// $sql = "SELECT * FROM audio_files WHERE id = {$audioFileId}";

$sql = "SELECT af.*, am.*, cf.* FROM audio_files af, audio_metadata am, cover_files cf WHERE 
        af.id = {$audioFileId}
        AND am.id = af.id 
        AND cf.id = af.id";

$sql2 = "SELECT id FROM audio_files ORDER BY id DESC LIMIT 1;";

$result = mysqli_query($mysqli, $sql);
$result2 = mysqli_query($mysqli, $sql2);

$data1 = array();
$data2 = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data1[] = $row;
        if (mysqli_num_rows($result2) > 0) {
            while ($row = mysqli_fetch_assoc($result2)) {
                $data2 = $row;
            }
        }
    }
}

$data = [$data1, $data2];

echo json_encode($data);
?>