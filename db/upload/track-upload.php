<?php

session_start();

$title = $_POST["beat-title"];
$genre = $_POST["beat-genre"];
$tempo = $_POST["beat-tempo"];
$key = $_POST["beat-key"];
$desc = $_POST["beat-desc"];
$beat_name_var = $_POST["beat-name-var"];
$username = $_SESSION["username"];
$user_id = $_SESSION["user_id"];
$file = $_FILES["audio-file"];

$dirName = "user-directories";

$response = array(
    'status' => 0,
    'message' => 'Something is wrong',
);

// VERIFY AUDIO FILE

if ($_GET["action"] == "verify") {
    if ($file["error"] !== UPLOAD_ERR_OK) {
        switch ($_FILES["audio-file"]["error"]) {
            case UPLOAD_ERR_PARTIAL:
                $response['status'] = "101";
                $response['message'] = "File only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $response['status'] = "102";
                $response['message'] = "No file was uploaded";
                break;
            case UPLOAD_ERR_EXTENSION:
                $response['status'] = "103";
                $response['message'] = "File upload stopped by a PHP extension";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $response['status'] = "104";
                $response['message'] = "File exceeds MAX_FILE_SIZE in the HTML form";
                break;
            case UPLOAD_ERR_INI_SIZE:
                $response['status'] = "105";
                $response['message'] = "File exceeds upload_max_filesize in php.ini";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $response['status'] = "106";
                $response['message'] = "Temporary folder not found";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $response['status'] = "107";
                $response['message'] = "Failed to write file";
                break;
            default:
                $response['status'] = "108";
                $response['message'] = "Unknown upload error";
                break;
        }

    } else {
        if ($file["size"] > 50000000) {
            $response['status'] = "109";
            $response['message'] = "File too large (max 50MB)";
        } else {

            // Use fileinfo to get the mime type
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $finfo->file($file["tmp_name"]);

            $mime_types = ["audio/mpeg", "audio/wav", "audio/flac"];

            if (!in_array($file["type"], $mime_types)) {
                // exit("Invalid file type");
                $response['status'] = "110";
                $response['message'] = "Invalid file type";
            } else {
                $response['status'] = "1";
                $response['message'] = "File is valid";
            }
        }
    }
} else if ($_GET["action"] == "upload") {

    // UPLOAD AUDIO FILE

    $pathinfo = pathinfo($_FILES["audio-file"]["name"]);
    $pathext = strtolower($pathinfo["extension"]);
    $base = $pathinfo["filename"];
    $filter = preg_replace("/[^\w-]/", "_", $beat_name_var);
    $base = preg_replace("/[^\w-]/", "_", $base);
    $filename = $base . "." . $pathinfo["extension"];

    mkdir("../$dirName/$username/tracks/$filter", 0777, true);
    $file_destination = "../" . $dirName . "/" . $_SESSION["username"] . "/tracks/" . $base . "/beat." . $pathext;

    // Add a numeric suffix if the file already exists
    $i = 1;

    while (file_exists($file_destination)) {
        $response['message'] = "A beat with the same name is already uploaded";
    }

    if (!move_uploaded_file($_FILES["audio-file"]["tmp_name"], $file_destination)) {
        $response['message'] = "Error moving the uploaded file (php)";
    } else {

        // UPLOAD COVER FILE

        $pathinfo = pathinfo($_FILES["beat-cover"]["name"]);
        $pathext = strtolower($pathinfo["extension"]);
        $base = $pathinfo["filename"];
        $filename = $base . "." . $pathinfo["extension"];
        $cover_destination = "../" . $dirName . "/" . $_SESSION["username"] . "/tracks/" . $filter . "/cover." . $pathext;

        // Add a numeric suffix if the file already exists
        $i = 1;

        while (file_exists($cover_destination)) {
            $response['message'] = "A cover with the same name is already uploaded";
        }

        if (!move_uploaded_file($_FILES["beat-cover"]["tmp_name"], $cover_destination)) {
            $response['message'] = "Error moving the uploaded file (php)";
        } else {

            // UPLOAD ALL TO THE DATABASE

            $mysqli = require "../database.php";

            $sql = "INSERT INTO user_tracks (
                    user_id,
                    title,
                    genre,
                    tempo,
                    beat_key,
                    beat_desc,
                    file_url,
                    cover_url,
                    date_time,
                    timezone
                    ) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $mysqli->stmt_init();

            if (!$stmt->prepare($sql)) {
                $response['message'] = "SQL error: " . $mysqli->error;
            }

            $stmt->bind_param(
                "ississssss",
                $user_id,
                $title,
                $genre,
                $tempo,
                $key,
                $desc,
                $file_destination,
                $cover_destination,
                date("Y-m-d H:i:s", strtotime("+1 hours")),
                date_default_timezone_get()
            );

            if ($stmt->execute()) {
                $response['status'] = 1;
                $response['message'] = "Beat uploaded successfully";
                mysqli_close($mysqli);
            } else {
                $response['status'] = "0";
                $response['message'] = "Beat upload error";
                die($mysqli->error . " " . $mysqli->errno);
            }
        }
    }
}

echo json_encode($response);

?>