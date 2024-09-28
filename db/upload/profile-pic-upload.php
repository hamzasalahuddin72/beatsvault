<?php

session_start();

$current_user_id = $_SESSION["user_id"];
$dirName = "user-directories";

$response = array(
    'status' => 0,
    'message' => 'Something is wrong'
);

// Replace any characters not \w- in the original filename
$pathinfo = pathinfo($_FILES["user-profile-pic"]["name"]);

$pathext = $pathinfo["extension"];

$base = $pathinfo["filename"];

$filename = $base . "." . $pathinfo["extension"];

$destination = "../" . $dirName . "/" . $_SESSION["username"] . "/userdata/profile_pic." . $pathext;

// Add a numeric suffix if the file already exists
$i = 1;

while (file_exists($destination)) {
    $response['message'] = "Please change the filename...";
}

if (!move_uploaded_file($_FILES["user-profile-pic"]["tmp_name"], $destination)) {
    $response['message'] = "Error moving the uploaded file (php)";
}

$file_url = $destination;

$response['fileurl'] = $file_url;

/*-----------insert data to table---------*/

$mysqli = require "../database.php";

$sql = "UPDATE user_data SET profile_pic_url = '$file_url'  WHERE id = $current_user_id";

$stmt = $mysqli->prepare($sql);
$stmt->execute();

if (!$stmt->prepare($sql)) {
    $response['message'] = "SQL error: " . $mysqli->error;
}

if ($stmt->execute()) {
    $response['status'] = 1;
    $response['message'] = "Profile picture has been uploaded into the database (SQL)";
    mysqli_close($mysqli);
} else {
    $response['status'] = "0";
    $response['message'] = "Profile picture upload error (SQL)";
    die($mysqli->error . " " . $mysqli->errno);
}

echo json_encode($response);

?>