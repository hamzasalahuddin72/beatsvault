<?php

session_start();

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "User not authenticated"]);
    exit;
}

$username = $_GET["user"];
$user_id = $_SESSION['user_id'];

$mysqli = require "../database.php";

// Prepared statement to fetch user data and profile
$sql1 = $mysqli->prepare("
    SELECT ud.*, au.username, au.firstname, au.lastname, au.email, au.public 
    FROM all_users au 
    JOIN user_data ud ON au.id = ud.id 
    WHERE au.username = ? 
    LIMIT 1
");
$sql1->bind_param('s', $username);
$sql1->execute();
$result1 = $sql1->get_result();

// Prepared statement to check if the current user is following the profile user
$sql2 = $mysqli->prepare("
    SELECT followed_id 
    FROM user_follow 
    WHERE follower_id = ? 
    AND followed_id = (SELECT id FROM all_users WHERE username = ?)
");
$sql2->bind_param('is', $user_id, $username);
$sql2->execute();
$result2 = $sql2->get_result();

// Prepared statement to fetch audio metadata for the profile user
$sql3 = $mysqli->prepare("
    SELECT au.username, ud.profile_pic_url, am.* 
    FROM all_users au 
    JOIN user_data ud ON au.id = ud.id 
    JOIN audio_metadata am ON am.user_id = ud.id 
    WHERE 
        (
            au.id = ? 
            OR au.id = (SELECT followed_id FROM user_follow WHERE follower_id = ? AND followed_id = (SELECT id FROM all_users WHERE username = ?))
            OR (au.username = ? AND au.public = 1)
        )
");
$sql3->bind_param('iiss', $user_id, $user_id, $username, $username);
$sql3->execute();
$result3 = $sql3->get_result();

$selUser = [];
$selUserTracks = [];

// Check if user profile data was found
if ($result1->num_rows > 0) {
    $selUser = $result1->fetch_assoc(); // Fetch user profile data

    // Check if the current user is viewing their own profile
    if ($selUser['id'] == $user_id) {
        $selUser['status'] = 'current_user';
    } else {
        // Check if the current user is following the profile user
        if ($result2->num_rows > 0) {
            $selUser['status'] = 'is_followed';
        }
    }
}

// Fetch audio metadata for the user
if ($result3->num_rows > 0) {
    while ($row = $result3->fetch_assoc()) {
        $selUserTracks[] = $row;
    }
}

$data = [$selUser, $selUserTracks];
echo json_encode($data);

// Close the statements and database connection
$sql1->close();
$sql2->close();
$sql3->close();
$mysqli->close();

?>