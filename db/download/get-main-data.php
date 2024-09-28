<?php

$mysqli = require "../database.php";

$sql1 = "SELECT * FROM genres_list";
$sql2 = "SELECT * FROM daws_list";
$sql3 = "SELECT * FROM instruments_list";

$result1 = $mysqli->query($sql1);
$result2 = $mysqli->query($sql2);
$result3 = $mysqli->query($sql3);

$genres = mysqli_fetch_all($result1, MYSQLI_ASSOC);
$daws = mysqli_fetch_all($result2, MYSQLI_ASSOC);
$instruments = mysqli_fetch_all($result3, MYSQLI_ASSOC);

$data = [$genres, $daws, $instruments];

echo json_encode($data);

mysqli_close($mysqli);

?>