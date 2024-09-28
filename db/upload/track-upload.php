<?php

session_start();

// Check if user is authenticated
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["username"])) {
    echo json_encode(['status' => 0, 'message' => 'User not authenticated']);
    exit;
}

$title = $_POST["beat-title"] ?? '';
$genre = $_POST["beat-genre"] ?? '';
$tempo = $_POST["beat-tempo"] ?? '';
$key = $_POST["beat-key"] ?? '';
$desc = $_POST["beat-desc"] ?? '';
$beat_name_var = $_POST["beat-name-var"] ?? '';
$username = $_SESSION["username"];
$user_id = $_SESSION["user_id"];
$file = $_FILES["audio-file"];
$cover = $_FILES["beat-cover"];

$dirName = "../user-directories";  // Base directory outside of root

$response = [
    'status' => 0,
    'message' => 'Something went wrong',
];

// Validate 'action' parameter
$action = $_GET["action"] ?? '';

if ($action == "verify") {
    // Verify audio file
    if ($file["error"] !== UPLOAD_ERR_OK) {
        $error_messages = [
            UPLOAD_ERR_PARTIAL => "File only partially uploaded",
            UPLOAD_ERR_NO_FILE => "No file was uploaded",
            UPLOAD_ERR_EXTENSION => "File upload stopped by a PHP extension",
            UPLOAD_ERR_FORM_SIZE => "File exceeds MAX_FILE_SIZE in the HTML form",
            UPLOAD_ERR_INI_SIZE => "File exceeds upload_max_filesize in php.ini",
            UPLOAD_ERR_NO_TMP_DIR => "Temporary folder not found",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file",
        ];
        $response['status'] = "101";
        $response['message'] = $error_messages[$file["error"]] ?? "Unknown upload error";
    } else {
        // File size check
        if ($file["size"] > 50000000) { // 50MB limit
            $response['status'] = "109";
            $response['message'] = "File too large (max 50MB)";
        } else {
            // Validate file type
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $finfo->file($file["tmp_name"]);
            $allowed_mime_types = ["audio/mpeg", "audio/wav", "audio/flac"];

            if (!in_array($mime_type, $allowed_mime_types)) {
                $response['status'] = "110";
                $response['message'] = "Invalid file type";
            } else {
                $response['status'] = "1";
                $response['message'] = "File is valid";
            }
        }
    }
} elseif ($action == "upload") {

    // Create user directories if they do not exist
    $trackDir = "$dirName/$username/tracks/$beat_name_var";
    if (!file_exists($trackDir)) {
        if (!mkdir($trackDir, 0777, true)) {
            $response['message'] = "Failed to create directory: $trackDir";
            echo json_encode($response);
            exit;
        }
    }

    // Prepare file destinations
    $audio_pathinfo = pathinfo($file["name"]);
    $audio_base = preg_replace("/[^\w-]/", "_", $audio_pathinfo["filename"]);
    $audio_extension = strtolower($audio_pathinfo["extension"]);
    $audio_filename = $audio_base . "." . $audio_extension;
    $audio_destination = "$trackDir/beat.$audio_extension";

    // Check if audio file exists and generate a unique name if needed
    $i = 1;
    while (file_exists($audio_destination)) {
        $audio_filename = $audio_base . "_" . $i . "." . $audio_extension;
        $audio_destination = "$trackDir/$audio_filename";
        $i++;
    }

    // Move uploaded audio file
    if (!move_uploaded_file($file["tmp_name"], $audio_destination)) {
        $response['message'] = "Error moving the uploaded audio file";
        echo json_encode($response);
        exit;
    }

    // Process cover file
    $cover_pathinfo = pathinfo($cover["name"]);
    $cover_base = preg_replace("/[^\w-]/", "_", $cover_pathinfo["filename"]);
    $cover_extension = strtolower($cover_pathinfo["extension"]);
    $cover_filename = $cover_base . "." . $cover_extension;
    $cover_destination = "$trackDir/cover.$cover_extension";

    // Check if cover file exists and generate a unique name if needed
    $i = 1;
    while (file_exists($cover_destination)) {
        $cover_filename = $cover_base . "_" . $i . "." . $cover_extension;
        $cover_destination = "$trackDir/$cover_filename";
        $i++;
    }

    // Move uploaded cover file
    if (!move_uploaded_file($cover["tmp_name"], $cover_destination)) {
        $response['message'] = "Error moving the uploaded cover file";
        echo json_encode($response);
        exit;
    }

    // Insert into database
    $mysqli = require "../database.php";

    $sql = "
        INSERT INTO audio_metadata (
            user_id, title, genre, tempo, beat_key, beat_desc, audio_url, cover_url, date_time, timezone
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        $response['message'] = "SQL error: " . $mysqli->error;
        echo json_encode($response);
        exit;
    }

    $datetime = date("Y-m-d H:i:s", strtotime("+1 hours"));
    $timezone = date_default_timezone_get();

    $stmt->bind_param(
        "ississssss",
        $user_id, $title, $genre, $tempo, $key, $desc, $audio_destination, $cover_destination, $datetime, $timezone
    );

    if ($stmt->execute()) {
        $response['status'] = 1;
        $response['message'] = "Beat uploaded successfully";
    } else {
        $response['message'] = "Database error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $mysqli->close();
}

echo json_encode($response);

?>