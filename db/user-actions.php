<?php

session_start();

$current_user_id = $_SESSION["user_id"];
$date = date("Y-m-d H:i:s", strtotime("+1 hours"));
$timezone = date_default_timezone_get();
$seen = 9;

$response = array(
    'status' => 0,
    'message' => 'Something is wrong',
);

$mysqli = require "./database.php";

if ($_GET["action"] == "isTyping") {

    $sql = "REPLACE INTO user_chat (from_user, to_user, seen) VALUES (?, ?, ?)";

    $stmt = $mysqli->stmt_init();

    if (!$stmt->prepare($sql)) {
        $response['message'] = "SQL error: " . $mysqli->error;
    }

    $stmt->bind_param(
        "iii",
        $current_user_id,
        $_GET["to_user"],
        $seen
    );

    if ($stmt->execute()) {
        $response['status'] = 1;
        $response['message'] = "success (isTyping)";
        mysqli_close($mysqli);
    } else {
        $response['status'] = "0";
        $response['message'] = "isTyping error";
        die($mysqli->error . " " . $mysqli->errno);
    }
}

if ($_GET["action"] == "notTyping") {
    $sql = "DELETE FROM user_chat WHERE from_user = '$current_user_id' AND to_user = {$_GET["to_user"]} AND seen = '$seen'";

    if (mysqli_query($mysqli, $sql)) {

        $sql = "SELECT MAX(id) as id FROM user_chat";

        $result = mysqli_query($mysqli, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $num = intval($row['id']) + 1;
                $sql = "ALTER TABLE user_chat AUTO_INCREMENT = $num";

                if (mysqli_query($mysqli, $sql)) {
                    $response['status'] = 1;
                    $response['message'] = "success (notTyping)";
                    mysqli_close($mysqli);
                }
            }
        }
    } else {
        $response['status'] = 0;
        $response['message'] = "Something went wrong. Please try again";
        die($mysqli->error . " " . $mysqli->errno);
    }
}

if ($_GET["action"] == "msgSeen") {
    $sql = "UPDATE user_chat SET seen = 1 
            WHERE from_user = {$_GET["to_user"]}
            AND to_user = '$current_user_id'
            AND seen = 0";

    $stmt = $mysqli->prepare($sql);

    if ($stmt->execute()) {
        $response['status'] = 1;
        $response['message'] = "message seen";
        mysqli_close($mysqli);
    } else {
        $response['status'] = 0;
        $response['message'] = "Something went wrong. Please try again";
        die($mysqli->error . " " . $mysqli->errno);
    }
}

if ($_GET["action"] == "msgSend") {
    $message = $_GET["message"];

    $sql = "DELETE FROM user_chat WHERE from_user = $current_user_id AND to_user = {$_GET["to_user"]} AND seen = $seen";

    if (mysqli_query($mysqli, $sql)) {

        $sql = "SELECT MAX(id) as id FROM user_chat";

        $result = mysqli_query($mysqli, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $num = intval($row['id']) + 1;

                $sql = "ALTER TABLE user_chat AUTO_INCREMENT = $num";

                if (mysqli_query($mysqli, $sql)) {
                    $sql = "INSERT INTO user_chat (id, from_user, to_user, message, date_time, timezone) VALUES (?,?,?,?,?,?)";

                    $stmt = $mysqli->stmt_init();

                    if (!$stmt->prepare($sql)) {
                        $response['message'] = "SQL error: " . $mysqli->error;
                    }

                    $stmt->bind_param(
                        "iiisss",
                        $num,
                        $_SESSION["user_id"],
                        $_GET["to_user"],
                        $message,
                        date("Y-m-d H:i:s", strtotime("+1 hours")),
                        // date("Y-m-d H:i:s"),
                        $timezone
                    );

                    if ($stmt->execute()) {
                        $response['status'] = 1;
                        $response['message'] = "sent";
                        mysqli_close($mysqli);
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Message could not be sent";
                        die($mysqli->error . " " . $mysqli->errno);
                    }
                }
            }
        }
    } else {
        $response['status'] = 0;
        $response['message'] = "Something went wrong. Please try again";
        die($mysqli->error . " " . $mysqli->errno);
    }
}

if ($_GET["action"] == 'follow') {

    $sql = "INSERT INTO user_follow (follower_id, followed_id, date_time, timezone) VALUES (?, ?, ?, ?)";

    $stmt = $mysqli->stmt_init();

    if (!$stmt->prepare($sql)) {
        $response['message'] = "SQL error: " . $mysqli->error;
    }

    $stmt->bind_param(
        "iiss",
        $current_user_id,
        $_GET["to_user"],
        $date,
        $timezone
    );

    if ($stmt->execute()) {

        $sql = "UPDATE all_users SET follower_count = follower_count + 1 WHERE id = {$_GET["to_user"]}";
        
        $stmt = $mysqli->prepare($sql);
        
        if ($stmt->execute()) {
            $sql = "UPDATE all_users SET following_count = following_count + 1 WHERE id = $current_user_id";
            
            $stmt = $mysqli->prepare($sql);

            if ($stmt->execute()) {
                $response['status'] = 1;
                $response['message'] = "user followed";
                mysqli_close($mysqli);
            } else {
                $response['status'] = 0;
                $response['message'] = "Something went wrong. Please try again";
                die($mysqli->error . " " . $mysqli->errno);
            }
            
        } else {
            $response['status'] = 0;
            $response['message'] = "Something went wrong. Please try again";
            die($mysqli->error . " " . $mysqli->errno);
        }

    } else {
        $response['status'] = 0;
        $response['message'] = "Something went wrong. Please try again";
        die($mysqli->error . " " . $mysqli->errno);
    }

}

if ($_GET["action"] == 'unfollow') {
    $sql = "DELETE FROM user_follow WHERE follower_id = $current_user_id AND followed_id = {$_GET["to_user"]}";

    if (mysqli_query($mysqli, $sql)) {

        $sql = "UPDATE all_users SET follower_count = follower_count - 1 WHERE id = {$_GET["to_user"]}";

        $stmt = $mysqli->prepare($sql);

        if ($stmt->execute()) {

            $sql = "UPDATE all_users SET following_count = following_count - 1 WHERE id = $current_user_id";

            $stmt = $mysqli->prepare($sql);

            if ($stmt->execute()) {
                $response['status'] = 2;
                $response['message'] = "user unfollowed";
                mysqli_close($mysqli);
            } else {
                $response['status'] = 0;
                $response['message'] = "Something went wrong. Please try again";
                die($mysqli->error . " " . $mysqli->errno);
            }
        } else {
            $response['status'] = 0;
            $response['message'] = "Something went wrong. Please try again";
            die($mysqli->error . " " . $mysqli->errno);
        }
    } else {
        $response['status'] = 0;
        $response['message'] = "Something went wrong. Please try again";
        die($mysqli->error . " " . $mysqli->errno);
    }
}

if ($_GET["action"] == 'public-mode') {
    $sql = "UPDATE all_users SET public = {$_GET["public"]} WHERE id = $current_user_id";

    $stmt = $mysqli->prepare($sql);

    if ($stmt->execute()) {
        if ($_GET["public"] == 1) {
            $response['status'] = 1;
            $response['message'] = "Your account is now public";
        } else if ($_GET["public"] == 0) {
            $response['status'] = 2;
            $response['message'] = "Your account is now private";
        }
        mysqli_close($mysqli);
    } else {
        $response['status'] = 0;
        $response['message'] = "Something went wrong. Please try again";
        die($mysqli->error . " " . $mysqli->errno);
    }
}

echo json_encode($response);

?>