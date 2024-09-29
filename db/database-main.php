<?php

$host = "localhost";
$db = "beatsvault_main";
$dbuser = "root";
$dbpassword = "";

$mysqli = new mysqli(
                    hostname: $host,
                    database: $db,
                    username: $dbuser,
                    password: $dbpassword);

if ($mysqli->connect_errno) {
    echo "Connection error: " . $mysqli->connect_error;
}

return $mysqli;

?>