<?php

session_start();

session_destroy();

unset($_SESSION["user_id"]);
unset($_SESSION["username"]);
unset($_SESSION["start"]);
unset($_SESSION["duration"]);

header("Location: /login");

?>