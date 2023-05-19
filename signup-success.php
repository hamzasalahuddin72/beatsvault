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
            header("Location: /beatsvault/login");
        } else {
            $sql = "SELECT * FROM all_users
            WHERE id = {$_SESSION["user_id"]} AND signup_complete = 1";

            $result = $mysqli->query($sql);
            $user = $result->fetch_assoc();

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    header("Location: /beatsvault/index");
                    unset($_SESSION["signup-incomplete"]);
                } else {
                    $_SESSION["signup-incomplete"] = $_SESSION['user_id'];
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
    header("Location: /beatsvault/login");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main-style.css">
    <link rel="stylesheet" href="css/signup-session.css">
    <link rel="stylesheet" href="css/countries-list.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script defer src="js/signup-session.js"></script>
    <script type="text/javascript" src="js/lastfm.api.md5.js"></script>
    <script type="text/javascript" src="js/lastfm.api.js"></script>
    <title>One last step - BEATSVAULT</title>
</head>

<body>

    <?php include("templates/navbar.php") ?>

    <div id="wrapper">

        <div class="loading-box lb-main">
            <img id="loading-bar" src="css/misc/img/loading-snake.gif">
            <br>
            <span style="text-align: center">{ Please wait.. }</span>
        </div>

        <span id="status-bar">
            <div id="status-bar-wrapper">
                <div id="global-progress-bar"></div>
                <div id="form-response">message</div>
            </div>
        </span>

        <div id="slider">

            <div class="slide-wrapper acc-type-slide">
                <div class="post-join-wrapper">
                    <div class="pj-align-wrapper">
                        <div class="slide-head">
                            <h1>Choose your role</h1>
                        </div>
                        <br>
                        <br>
                        <div class="post-join-form account-type-select">
                            <div class="acc-type-btn producer-acc-type">
                                <div class="acc-type-bg producer-acc-bg">
                                    <div id="checked-Producer" class="global-popup-session">Producer</div>
                                    <input class="radio-input" onclick="continueSignup(1, 'Producer')" type="radio"
                                        name="user-account-type" value="Producer">
                                </div>
                            </div>
                            <div class="acc-type-btn artist-acc-type">
                                <div class="acc-type-bg artist-acc-bg">
                                    <div id="checked-Artist" class="global-popup-session">Artist</div>
                                    <input class="radio-input" onclick="continueSignup(1, 'Artist')" type="radio"
                                        name="user-account-type" value="Artist">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="slide-wrapper acc-gender-slide">
                <div class="post-join-wrapper">
                    <div class="pj-align-wrapper">
                        <div class="slide-head">
                            <div class="icon-mask" id="previous-slide" onclick="previousSlide(-1)"></div>
                            <h1>Select your gender</h1>
                        </div>
                        <br>
                        <br>
                        <div class="post-join-form user-gender-form">
                            <label id="checked-male" class="select-card-btn" onclick="continueSignup(2, 'male')">
                                <input class="radio-input" type="radio" name="user-gender" value="Male">
                                <span class="upload-span">Male</span>
                                <img src="css/misc/img/blue-icons/gender-male.png" class="card-btn-icon">
                            </label>
                            <label id="checked-female" class="select-card-btn" onclick="continueSignup(2, 'female')">
                                <input class="radio-input" type="radio" name="user-gender" value="Female">
                                <span class="upload-span">Female</span>
                                <img src="css/misc/img/blue-icons/gender-female.png" class="card-btn-icon">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="slide-wrapper acc-profile-slide">
                <div class="post-join-wrapper">
                    <div class="pj-align-wrapper">
                        <div class="slide-head">
                            <div class="icon-mask" id="previous-slide" onclick="previousSlide(-2)"></div>
                            <h1>Upload a profile picture</h1>
                        </div>
                        <br>
                        <br>
                        <form class="post-join-form user-profile-pic-form">
                            <label id="profile-pic-upload" class="select-card-btn">
                                <input class="radio-input" type="file" accept="image/*" name="user-profile-pic"
                                    id="user-profile-pic" onchange="continueSignup(3)">
                                <span class="upload-span pp-upload-span">Upload a picture</span>
                                <img src="css/misc/img/blue-icons/user-profile-pic.png" class="card-btn-icon"
                                    id="profile-pic-preview">
                            </label>
                        </form>
                        <div class="continue-wrapper">
                            <div class="icon-mask next-slide" id="next-slide-4" onclick="continueSignup(3.5)"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="slide-wrapper acc-country-slide">
                <div class="post-join-wrapper">
                    <div class="pj-align-wrapper">
                        <div class="slide-head">
                            <div class="icon-mask" id="previous-slide" onclick="previousSlide(-3)"></div>
                            <h1>Where are you from ?</h1>
                        </div>
                        <br>
                        <br>
                        <div class="post-join-form all-countries-list"></div>
                    </div>
                </div>
            </div>

            <div class="slide-wrapper acc-genre-slide">
                <div class="post-join-wrapper">
                    <div class="pj-align-wrapper">
                        <div class="slide-head">
                            <div class="icon-mask" id="previous-slide" onclick="previousSlide(-4)"></div>
                            <h1>Select your three favourite genres ?</h1>
                        </div>
                        <br>
                        <br>
                        <div class="genres-list"></div>
                    </div>
                </div>
            </div>

            <!-- <div class="slide-wrapper acc-artist-pref artist-pref-slide1"> -->
            <div class="slide-wrapper acc-tags-slide">
                <div class="post-join-wrapper">
                    <div class="pj-align-wrapper">
                        <div class="slide-head">
                            <div class="icon-mask" id="previous-slide" onclick="previousSlide(-5)"></div>
                            <h1>Select five tags</h1>
                        </div>
                        <br>
                        <br>
                        <input type="text" id="search-engine" placeholder="Search tags..">
                        <br>
                        <br>
                        <div class="selected-tags-list"></div>
                        <br>
                        <div class="tags-list"></div>
                        <br>
                        <br>
                        <span id="tags-count"></span>
                        <div class="loading-box lb-tags">
                            <img id="loading-bar" src="css/misc/img/loading-snake.gif">
                            <br>
                            <span style="text-align: center">{ Please wait.. }</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="slide-wrapper acc-artist-pref artist-pref-slide2"> -->
            <div class="slide-wrapper acc-bpm-slide">
                <div class="post-join-wrapper">
                    <div class="pj-align-wrapper">
                        <div class="slide-head">
                            <div class="icon-mask" id="previous-slide" onclick="previousSlide(-6)"></div>
                            <h1>Select your BPM range</h1>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <div class="range-slider-wrapper">
                            <span class="global-range-tooltip"></span>
                            <input class="global-range-slider" name="user-selected-tempo" onchange="continueSignup(7)"
                                type="range" value="100" max="200" id="bpm-range-input">
                        </div>
                        <div class="continue-wrapper">
                            <div class="icon-mask next-slide" id="next-slide-5" onclick="continueSignup(7.5)"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="slide-wrapper acc-instruments-slide">
                <div class="post-join-wrapper">
                    <div class="pj-align-wrapper">
                        <div class="slide-head">
                            <div class="icon-mask" id="previous-slide" onclick="previousSlide(-7)"></div>
                            <h1 id="instruments-head-title">Select the instruments you use ?</h1>
                        </div>
                        <br>
                        <br>
                        <div class="instruments-list"></div>
                        <div class="continue-wrapper">
                            <div class="icon-mask next-slide" id="next-slide-6" onclick="continueSignup(8.5)"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="slide-wrapper acc-producer-pref producer-pref-slide1">
                <div class="post-join-wrapper">
                    <div class="pj-align-wrapper">
                        <div class="slide-head">
                            <div class="icon-mask" id="previous-slide" onclick="previousSlide(-8)"></div>
                            <h1>Select your Digital Audio Workstations (DAW) ?</h1>
                        </div>
                        <br>
                        <br>
                        <br>
                        <div class="daws-list">
                        </div>
                        <div class="continue-wrapper">
                            <div class="icon-mask next-slide" id="next-slide-7" onclick="continueSignup(9.5)"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="slide-wrapper acc-review-slide">
                <div class="post-join-wrapper">
                    <div class="pj-align-wrapper">
                        <div class="slide-head">
                            <div class="icon-mask" id="previous-slide" onclick="previousSlide(-9)"></div>
                            <h1>Review</h1>
                        </div>
                        <br>
                        <br>
                        <br>
                        <table id="review-table"></table>
                        <div class="continue-wrapper">
                            <button id="signup-complete-btn" onclick="continueSignup(10)">
                                Complete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    </div>

</body>

</html>