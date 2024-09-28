<?php

$host = "localhost";
$db = "beatsvault_users";
$dbuser = "root";
$dbpassword = "";

$mysqli = new mysqli(hostname: $host,
                    database: $db,
                    username: $dbuser,
                    password: $dbpassword);

if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;

?>