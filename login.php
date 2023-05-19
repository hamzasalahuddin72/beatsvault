<?php

session_start();

$now = time();

if (isset($_SESSION["user_id"]) && ($now - $_SESSION["start"]) < $_SESSION["duration"]) {

    $mysqli = require "db/database.php";

    $sql = "SELECT * FROM all_users WHERE id = {$_SESSION['user_id']}";

    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $sql = "SELECT * FROM all_users
            WHERE id = {$_SESSION["user_id"]} AND signup_complete = 1";

            $result = $mysqli->query($sql);
            $user = $result->fetch_assoc();

            if ($result) {
                if (mysqli_num_rows($result) == 0) {
                    header("Location: /beatsvault/signup-success");
                } else {
                    header("Location: /beatsvault/index");
                }
            } else {
                echo 'Error: ' . mysqli_error();
            }
        }

    } else {
        unset($_SESSION["user_id"]);
        unset($_SESSION["username"]);
        unset($_SESSION["start"]);
        unset($_SESSION["duration"]);
        echo 'Error: ' . mysqli_error();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="css/misc/img/logo-circle.png" />
    <link rel="stylesheet" href="css/main-style.css">
    <link rel="stylesheet" href="css/login.css">
    <script src="js/ui-functions.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <title>Log In - BEATSVAULT</title>

    <script>
        $(document).ready(function () {
            $(".login-form").submit(function (e) {
                e.preventDefault();
                var email = $("#email").val();
                var password = $("#password").val();
                var submit = $(".submit").val();
                $(".login-status").load("db/validation/login-validate.php", {
                    email: email,
                    password: password,
                    submit: submit
                })
            })
        })
        $(document).ready(function () {
            $(".signup-form").submit(function (e) {
                e.preventDefault();
                var firstname = $("#fname").val();
                var lastname = $("#lname").val();
                var email = $("#new-email").val();
                var username = $("#new-username").val();
                var password = $("#new-pw").val();
                var submit = $(".submit").val();
                $(".join-status").load("db/validation/signup-validate.php", {
                    firstname: firstname,
                    lastname: lastname,
                    username: username,
                    email: email,
                    password: password,
                    submit: submit
                })
            })
        })
    </script>

</head>

<body>

    <?php include("templates/navbar.php") ?>

    <!-- <div class="loading-box lb-main">
        <img id="loading-bar" src="css/misc/img/loading-snake.gif">
        <br>
        <span style="text-align: center">{ Please wait.. }</span>
    </div> -->

    <div id="wrapper">
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
        <div id="login-container">
            <div id="login-welcome">
                <p>WELCOME TO
                    <br><span>BEATSVAULT</span>
                </p>
                <p>Share
                    <br>Discover
                    <br>Collaborate
                </p>
            </div>
            <form class="user-form login-form" method="POST">
                <label>Username</label>
                <br>
                <input name="email" id="email" type="text" placeholder="Username / Email address">
                <br>
                <label>Password</label>
                <br>
                <input name="password" id="password" type="password" placeholder="Password">
                <br>
                <button id="login-submit" class="submit">Log In</button>
                <br>
                <a href="forgot-password.html">Recover password</a>
                <br>
                <a id="join-btn">Join</a>
                <label class="status-message login-status"></label>
            </form>
            <form class="user-form signup-form" method="POST">
                <label>First name</label>
                <br>
                <input name="fname" id="fname" type="text" placeholder="First name">
                <br>
                <label>Last name</label>
                <br>
                <input name="lname" id="lname" type="text" placeholder="Last name">
                <br>
                <label>Username</label>
                <br>
                <input name="new-username" id="new-username" type="text" placeholder="Producer name, artist name..">
                <br>
                <label>Email</label>
                <br>
                <input name="new-email" id="new-email" type="email" placeholder="Email address">
                <br>
                <label>Create a password</label>
                <br>
                <input name="new-pw" id="new-pw" type="password" placeholder="Create a password">
                <br>
                <button class="submit">Join</button>
                <br>
                <a id="login-btn">Log In</a>
                <label class="status-message join-status"></label>
            </form>
        </div>
    </div>

    <?php include("templates/footer.php") ?>

    <script src="js/function-login.js"></script>
</body>

</html>