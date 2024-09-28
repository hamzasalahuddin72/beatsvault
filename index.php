<?php

session_start();

$now = time();

if (isset($_SESSION["user_id"]) && ($now - $_SESSION["start"]) < $_SESSION["duration"]) {

    $mysqli = require "db/database.php";

    $sql = "SELECT * FROM all_users WHERE id = {$_SESSION['user_id']}";

    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();

    if ($result) {
        if (mysqli_num_rows($result) == 0) {
            header("Location: /login");
        } else {
            $sql = "SELECT * FROM all_users
            WHERE id = {$_SESSION["user_id"]} AND signup_complete = 1";

            $result = $mysqli->query($sql);
            $user = $result->fetch_assoc();

            if ($result) {
                if (mysqli_num_rows($result) == 0) {
                    header("Location: /signup-success");
                } else {
                }
            } else {
                echo 'Error: ' . mysqli_error();
            }
        }

    } else {
        echo 'Error: ' . mysqli_error();
    }

} else {
    unset($_SESSION["user_id"]);
    unset($_SESSION["username"]);
    unset($_SESSION["start"]);
    unset($_SESSION["duration"]);
    header("Location: /login");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="refresh" content="0; URL='/'" />  -->
    <link rel="shortcut icon" href="css/misc/img/logo-circle.png" />
    <link rel="stylesheet" href="css/main-style.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/upload-portal.css">
    <link rel="stylesheet" href="css/metadata-tray.css">
    <link rel="stylesheet" href="css/home-feed.css">
    <link rel="stylesheet" href="css/feed-sidebar.css">
    <link rel="stylesheet" href="css/visual-changes.css">
    <link rel="stylesheet" href="css/countries-list.css">
    <link rel="stylesheet" href="css/user-page.css">
    <link rel="stylesheet" href="css/settings.css">
    <link rel="stylesheet" href="css/optimization.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script defer src="js/ui-functions.js"></script>
    <script type="module" src="js/upload-files.js"></script>
    <script type="module" src="js/audio-handling.js"></script>
    <script type="module" src="url-router.js"></script>
    <script type="module" src="js/messenger.js"></script>
    <script type="module" src="js/settings.js"></script>
    <title>BEATSVAULT</title>
</head>

<body>

    <?php include("templates/navbar.php") ?>
    <div class="main-bg">
        <div class="main-bg-item main-bg-item-0">B</div>
        <div class="main-bg-item main-bg-item-1">B</div>
        <div class="main-bg-item main-bg-item-2">B</div>
        <div class="main-bg-item main-bg-item-3">B</div>
        <div class="main-bg-item main-bg-item-4">B</div>
        <div class="main-bg-item main-bg-item-5">B</div>
        <div class="main-bg-item main-bg-item-6">B</div>
        <div class="main-bg-item main-bg-item-7">B</div>
        <div class="main-bg-item main-bg-item-8">B</div>
        <div class="main-bg-item main-bg-item-9">B</div>
        <div class="main-bg-item main-bg-item-10">B</div>
        <div class="main-bg-item main-bg-item-11">B</div>
        <div class="main-bg-item main-bg-item-12">B</div>
        <div class="main-bg-item main-bg-item-13">B</div>
        <div class="main-bg-item main-bg-item-14">B</div>
        <div class="main-bg-item main-bg-item-15">B</div>
        <div class="main-bg-item main-bg-item-16">B</div>
        <div class="main-bg-item main-bg-item-17">B</div>
        <div class="main-bg-item main-bg-item-18">B</div>
        <div class="main-bg-item main-bg-item-19">B</div>
        <div class="main-bg-item main-bg-item-20">B</div>
        <div class="main-bg-item main-bg-item-21">B</div>
        <div class="main-bg-item main-bg-item-22">B</div>
        <div class="main-bg-item main-bg-item-23">B</div>
        <div class="main-bg-item main-bg-item-24">B</div>
        <div class="main-bg-item main-bg-item-25">B</div>
        <div class="main-bg-item main-bg-item-26">B</div>
        <div class="main-bg-item main-bg-item-27">B</div>
        <div class="main-bg-item main-bg-item-28">B</div>
        <div class="main-bg-item main-bg-item-29">B</div>
        <div class="main-bg-item main-bg-item-30">B</div>
        <div class="main-bg-item main-bg-item-31">B</div>
        <div class="main-bg-item main-bg-item-32">B</div>
        <div class="main-bg-item main-bg-item-33">B</div>
        <div class="main-bg-item main-bg-item-34">B</div>
        <div class="main-bg-item main-bg-item-35">B</div>
        <div class="main-bg-item main-bg-item-36">B</div>
        <div class="main-bg-item main-bg-item-37">B</div>
        <div class="main-bg-item main-bg-item-38">B</div>
        <div class="main-bg-item main-bg-item-39">B</div>
        <div class="main-bg-item main-bg-item-40">B</div>
        <div class="main-bg-item main-bg-item-41">B</div>
        <div class="main-bg-item main-bg-item-42">B</div>
        <div class="main-bg-item main-bg-item-43">B</div>
        <div class="main-bg-item main-bg-item-44">B</div>
        <div class="main-bg-item main-bg-item-45">B</div>
        <div class="main-bg-item main-bg-item-46">B</div>
        <div class="main-bg-item main-bg-item-47">B</div>
        <div class="main-bg-item main-bg-item-48">B</div>
        <div class="main-bg-item main-bg-item-49">B</div>
        <div class="main-bg-item main-bg-item-50">B</div>
        <div class="main-bg-item main-bg-item-51">B</div>
        <div class="main-bg-item main-bg-item-52">B</div>
        <div class="main-bg-item main-bg-item-53">B</div>
        <div class="main-bg-item main-bg-item-54">B</div>
        <div class="main-bg-item main-bg-item-55">B</div>
        <div class="main-bg-item main-bg-item-56">B</div>
        <div class="main-bg-item main-bg-item-57">B</div>
        <div class="main-bg-item main-bg-item-58">B</div>
        <div class="main-bg-item main-bg-item-59">B</div>
        <div class="main-bg-item main-bg-item-60">B</div>
        <div class="main-bg-item main-bg-item-61">B</div>
        <div class="main-bg-item main-bg-item-62">B</div>
        <div class="main-bg-item main-bg-item-63">B</div>
        <div class="main-bg-item main-bg-item-64">B</div>
        <div class="main-bg-item main-bg-item-65">B</div>
        <div class="main-bg-item main-bg-item-66">B</div>
        <div class="main-bg-item main-bg-item-67">B</div>
        <div class="main-bg-item main-bg-item-68">B</div>
        <div class="main-bg-item main-bg-item-69">B</div>
        <div class="main-bg-item main-bg-item-70">B</div>
        <div class="main-bg-item main-bg-item-71">B</div>
        <div class="main-bg-item main-bg-item-72">B</div>
        <div class="main-bg-item main-bg-item-73">B</div>
        <div class="main-bg-item main-bg-item-74">B</div>
        <div class="main-bg-item main-bg-item-75">B</div>
        <div class="main-bg-item main-bg-item-76">B</div>
        <div class="main-bg-item main-bg-item-77">B</div>
        <div class="main-bg-item main-bg-item-78">B</div>
        <div class="main-bg-item main-bg-item-79">B</div>
        <div class="main-bg-item main-bg-item-80">B</div>
        <div class="main-bg-item main-bg-item-81">B</div>
        <div class="main-bg-item main-bg-item-82">B</div>
        <div class="main-bg-item main-bg-item-83">B</div>
        <div class="main-bg-item main-bg-item-84">B</div>
        <div class="main-bg-item main-bg-item-85">B</div>
        <div class="main-bg-item main-bg-item-86">B</div>
        <div class="main-bg-item main-bg-item-87">B</div>
        <div class="main-bg-item main-bg-item-88">B</div>
        <div class="main-bg-item main-bg-item-89">B</div>
        <div class="main-bg-item main-bg-item-90">B</div>
        <div class="main-bg-item main-bg-item-91">B</div>
        <div class="main-bg-item main-bg-item-92">B</div>
        <div class="main-bg-item main-bg-item-93">B</div>
        <div class="main-bg-item main-bg-item-94">B</div>
        <div class="main-bg-item main-bg-item-95">B</div>
        <div class="main-bg-item main-bg-item-96">B</div>
        <div class="main-bg-item main-bg-item-97">B</div>
        <div class="main-bg-item main-bg-item-98">B</div>
        <div class="main-bg-item main-bg-item-99">B</div>
    </div>

    <!-- <div class="loading-box lb-main">
        <img id="loading-bar" src="css/misc/img/loading-snake.gif">
        <br>
        <span style="text-align: center">{ Please wait.. }</span>
    </div> -->

    <div class="global-popup-message"></div>

    <div id="top-wrapper">
        <div id="user-recent-tray">
            <div id="sub-recent-tray">
                <div class="loading-box lb-recent-tracks">
                    <img id="loading-bar" src="css/misc/img/loading-snake.gif">
                    <br>
                    <span style="text-align: center">{ Please wait.. }</span>
                </div>
                <div class="recent-tracks">
                </div>
                <div class="upload-tracks upload-portal-fx-before">

                    <div class="upload-portal">
                        <div class="loading-box lb-test">
                            <img id="loading-bar" src="css/misc/img/loading-snake.gif">
                            <br>
                            <span style="text-align: center">{ Please wait.. }</span>
                        </div>
                        <a id="upload-exit"><strong>X</strong></a>

                        <form class="upload-form">
                            <div class="file meta-upload" style="display: none; transform: scale(0);">
                                <input id="beat-name-var" value="" name="beat-name-var" style="display: none;">
                                <br>
                                <input type="file" accept="image/*" name="beat-cover" id="mt700" style="display: none;">
                                <label id="cover-upload" for="mt700" class=".cover-upload-before">
                                    <span class="upload-span">Upload cover</span>
                                    <img src="" alt="" id="cover-preview">
                                </label>
                                <br>
                                <button class="submit" id="upload-previous-btn"></button>
                                <span>Title</span>
                                <input id="mt701" type="text" placeholder="Give a title to your beat" name="beat-title">
                                <span>Genre</span>
                                <input id="mt702" type="text" placeholder="Genre" name="beat-genre">
                                <span>BPM</span>
                                <input id="mt703" type="number" placeholder="98, 160.." name="beat-tempo">
                                <span>Key</span>
                                <input id="mt704" type="text" placeholder="Key" name="beat-key">
                                <span>Description</span>
                                <textarea rows="5" cols="50" placeholder="Tell something about your beat"
                                    name="beat-desc" id="mt705" style="height:50px;"></textarea>
                                <label class="status-message join-status">
                                    <label class="error-message" style="display:none;"></label>
                                </label>
                                <br>
                                <button class="submit" id="upload-publish-btn" type="submit" name="submit">Publish
                                    beat</button>
                                <br>
                            </div>

                            <div class="file audio-upload">
                                <input type="file" name="audio-file" id="audio-upload" style="display: none;">
                                <label id="audio-upload" for="audio-upload">
                                    <img src="css/misc/img/audio-upload.png" alt="Image could not load">
                                    <span id="audio-upload-span">Upload beat</span>
                                </label>
                            </div>
                        </form>
                        <button class="submit" id="upload-next-btn">Next</button>
                    </div>

                    <a id="upload-button" class="album-cover-href">
                        <div id="upload-icon" class="icon-mask" alt="Icon could not load"></div>
                        Upload a beat
                    </a>
                </div>
            </div>
        </div>
        <div class="tracklist-options">
            <div class="btn option-btns">
                <span class="tooltip switch-layout-tooltip"></span>
                <div class="icon-mask layout-style"></div>
            </div>
        </div>
    </div>

    <div id="wrapper">
    </div>

    <div id="aa-wrapper">
    </div>

    <?php include("templates/audio-player.php") ?>
</body>

</html>