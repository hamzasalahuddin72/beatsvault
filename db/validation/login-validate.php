<?php

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $emptyEmail = false;
    $emptyPassword = false;
    $emailInvalid = false;
    $passwordInvalid = false;
    $joinFormEnabled = false;

    if (empty($email)) {
        echo "<label class='error-message'>Enter your email</label>";
        $emptyEmail = true;
    } else if (empty($password)) {
        echo "<label class='error-message'>Enter your password</label>";
        $emptyPassword = true;
    } else {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $mysqli = require "../database.php";
            $select = sprintf("SELECT * FROM all_users
                WHERE email = '%s'",
                $mysqli->real_escape_string($email)
            );
            $result = $mysqli->query($select);
            $user = $result->fetch_assoc();

            if ($user) {
                if (password_verify($password, $user["password_hash"])) {
                    session_start();
                    session_regenerate_id();
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["username"] = $user["username"];
                    $_SESSION["start"] = time();
                    $_SESSION["duration"] = 604800;
                    header("Refresh:3");
                    mysqli_close($mysqli);
                } else {
                    echo "<label class='error-message'>Invalid password</label>";
                    $passwordInvalid = true;
                }
            } else {
                echo "<label class='error-message'>Something is wrong</label>";
            }
        }
        ;
    }

} else {
    echo "There is an error";
}

?>

<script>

    var emptyEmail = "<?php echo $emptyEmail; ?>";
    var emptyPassword = "<?php echo $emptyPassword; ?>";
    var emailInvalid = "<?php echo $emailInvalid; ?>";
    var passwordInvalid = "<?php echo $passwordInvalid; ?>";
    var errorMessage = document.querySelector(".error-message");

    function resetForm() {
        $("#email, #password").removeClass("error-field");
    }

    function joinFormSwitch() {
        resetForm();
        errorMessage.remove();
        $("#email, #password").val("");
    }

    resetForm();

    if (emptyEmail == true || emailInvalid == true) {
        $("#email").addClass("error-field");
    } else if (emptyPassword == true || passwordInvalid == true) {
        $("#password").addClass("error-field");
    } else {
        window.location.href = "/";
    }

</script>