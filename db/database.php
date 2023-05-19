<?php

$host = "localhost";
$db = "hs902_beatsvault_users";
$dbuser = "hs902_hamzasalahuddin";
$dbpassword = "j1Wsm]JYw*nB";

$mysqli = new mysqli(hostname: $host,
                    database: $db,
                    username: $dbuser,
                    password: $dbpassword);

if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;

?>