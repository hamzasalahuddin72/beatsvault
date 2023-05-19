<?php

session_start();

if (isset($_SESSION["user_id"])) {

    $mysqli = require "db/database.php";

    $sql = "SELECT * FROM all_users WHERE id = {$_SESSION['user_id']}";

    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();

    if ($result) {
        if (mysqli_num_rows($result) == 0) {
            header("Location: /beatsvault/login");
        } else {
            $sql = "SELECT * FROM all_users
            WHERE id = {$_SESSION["user_id"]} AND signup_complete = 1";

            $result = $mysqli->query($sql);
            $user = $result->fetch_assoc();

            if ($result) {
                if (mysqli_num_rows($result) == 0) {
                    header("Location: /beatsvault/signup-success");
                } else {
                }
            } else {
                echo 'Error: ' . mysqli_error($mysqli);
            }
        }

    } else {
        echo 'Error: ' . mysqli_error($mysqli);
    }

} else {
    header("Location: /beatsvault/login");
}

?>

<div class="loading-box lb-user-page">
    <img id="loading-bar" src="css/misc/img/loading-snake.gif">
    <br>
    <span style="text-align: center">{ Please wait.. }</span>
</div>
<form id="profile-wrapper" method="post">
    <div id="pw-bg-wrapper">
        <div id="sub-profile-wrapper-left">
            <label id="pp-update-label" for="pp-update">
            </label>
        </div>
        <div id="sub-profile-wrapper-right">
            <div class="span-row">
                <div id="sel-username"></div>
                <div class="flag-icon" id="sel-country"></div>
                <a class="tm link-btns user-follow-btn" action="follow">Follow</a>
                <a class="tm link-btns user-message-btn">Message</a>
            </div>
            <h4 class="sel-type"></h4>
            <div class="genre-tray">
            </div>
            <label id="pp-bg-update-label" for="pp-bg-update">
            </label>
            <div class="chat-container-popup">
                <textarea type="text" name="message-input" class="short-message-input"
                    placeholder="type your message..."></textarea>
                <button class="short-message-send-btn">send</button>
            </div>
        </div>
    </div>
</form>
<div class="tracklist-options">
    <select name="sort" class="sort-feed">
        <!-- <option selected hidden>Sort By</option> -->
        <option selected value="newest">Newest</option>
        <option value="asc">Title (asc)</option>
        <option value="desc">Title (desc)</option>
        <option value="oldest">Oldest</option>
    </select>
    <div class="btn option-btns">
        <span class="tooltip switch-layout-tooltip">Grid</span>
        <div class="icon-mask layout-style"></div>
    </div>
</div>
<div class="home-feed-content user-profile-feed">
</div>