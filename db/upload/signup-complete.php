<?php

session_start();

$user_id = $_SESSION["user_id"];
$accType = $_POST["accType"];
$gender = $_POST["gender"];
$country = $_POST["country"];
$genresSelected = $_POST["genres"];
$tagsSelected = $_POST["tags"];
$bpm = $_POST["bpm"];
$instrumentsSelected = $_POST["instruments"];
$dawsSelected = $_POST["daws"];
$pending = "pending..";

$response = array(
    'status' => 0,
    'message' => 'Something is wrong'
);

/*-----------insert data to table---------*/

$mysqli = require "../database.php";

$sql = "INSERT INTO user_data (id, acc_type, gender, profile_pic_url, profile_cover_url, country, genres, tags, bpm, instruments, daws) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->stmt_init();

if (!$stmt->prepare($sql)) {
    $response['status'] = 0;
    $response['message'] = $mysqli->error . " " . $mysqli->errno;
}

$stmt->bind_param(
    "isssssssiss",
    $user_id,
    $accType,
    $gender,
    $pending,
    $pending,
    $country,
    implode(",", $genresSelected),
    implode(",", $tagsSelected),
    $bpm,
    implode(",", $instrumentsSelected),
    implode(",", $dawsSelected)
);

if ($stmt->execute()) {
    $sql2 = "UPDATE all_users SET signup_complete = 1 WHERE id = $user_id";

    $stmt2 = $mysqli->prepare($sql2);
    $stmt2->execute();

    if ($stmt2->execute()) {
        $response['status'] = 1;
        $response['message'] = "You have joined successfully..";
        mysqli_close($mysqli);
    } else {
        $response['status'] = 0;
        $response['message'] = $mysqli->error . " " . $mysqli->errno;
        die($mysqli->error . " " . $mysqli->errno);
    }
} else {
    $response['status'] = 0;
    $response['message'] = $mysqli->error . " " . $mysqli->errno;
    die($mysqli->error . " " . $mysqli->errno);
}

echo json_encode($response);

?>