<?php

session_start();

$sqlTo = "SELECT to_user FROM user_chat WHERE
        from_user = '{$_SESSION['user_id']}'
        OR to_user = '{$_SESSION['user_id']}'";

$sqlFrom = "SELECT from_user FROM user_chat WHERE
        from_user = '{$_SESSION['user_id']}'
        OR to_user = '{$_SESSION['user_id']}'";

$mysqli = require "../database.php";

$resultTo = mysqli_query($mysqli, $sqlTo);
$resultFrom = mysqli_query($mysqli, $sqlFrom);

$dataTo = array();
$dataFrom = array();

if (mysqli_num_rows($resultTo) > 0) {
    while ($row = mysqli_fetch_assoc($resultTo)) {
        if (!in_array($row["to_user"], $dataTo)) {
            array_push($dataTo, $row["to_user"]);
        }
        if (mysqli_num_rows($resultFrom) > 0) {
            while ($row = mysqli_fetch_assoc($resultFrom)) {
                if (!in_array($row["from_user"], $dataFrom)) {
                    array_push($dataFrom, $row["from_user"]);
                }
            }
        }
    }
}

$allToUsers = array_values(array_unique(array_merge($dataTo, $dataFrom)));
unset($allToUsers[array_search($_SESSION["user_id"], $allToUsers)]);
sort($allToUsers);

$chats = array();
$chatObj = array();
$chat = array();
$data = array();

foreach ($allToUsers as $toUser) {
    $mysqli = require "../database.php";

    $sql = "SELECT au.username, ud.profile_pic_url, uc.* FROM all_users au, user_data ud, user_chat uc
        WHERE ((uc.from_user = '$toUser' AND uc.to_user = {$_SESSION['user_id']})
        OR (uc.to_user = '$toUser' AND uc.from_user = {$_SESSION['user_id']}))
        AND au.id = '$toUser'
        AND ud.id = '$toUser'";

    $result = mysqli_query($mysqli, $sql);

    if (mysqli_num_rows($result) > 0) {
        $chatObj = [];
        $chat = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $chat[] = $row;
        }
    }
    
    array_push($chatObj, array("user_id" => $toUser, "chats" => $chat));
    array_push($chats, $chatObj);
}

$data = [0 => $chats, 1 => $_SESSION["user_id"]];

echo json_encode($data);

?>