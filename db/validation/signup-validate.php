<?php

if (isset($_POST['submit'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $signupComplete = 0;

    $emptyFirstname = false;
    $emptyLastname = false;
    $emptyUsername = false;
    $emptyEmail = false;
    $emptyPassword = false;
    $emailInvalid = false;
    $passwordInvalid = false;

    $dirName = "user-directories";

    if (empty($firstname)) {
        echo "<label class='error-message'>First name is required</label>";
        $emptyFirstname = true;
    } else if (empty($lastname)) {
        echo "<label class='error-message'>Last name is required</label>";
        $emptyLastname = true;
    } else if (empty($username)) {
        echo "<label class='error-message'>Username is required</label>";
        $emptyUsername = true;
    } else if (empty($email)) {
        echo "<label class='error-message'>Email address is required</label>";
        $emptyEmail = true;
    } else if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<label class='error-message'>Invalid Email</label>";
        $emailInvalid = true;
    } else if (empty($password)) {
        echo "<label class='error-message'>Create a password</label>";
        $emptyPassword = true;
    } else if (!empty($password) && !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[@$*.&_]/', $password) || strlen($password) < 8) {
        echo "<label class='error-message'>Invalid password</label>";
        $passwordInvalid = true;
    } else {
        /*-----------insert data to table---------*/

        $encrypt = password_hash($password, PASSWORD_DEFAULT);

        $mysqli = require "../database.php";

        $sql = "INSERT INTO all_users (firstname, lastname, username, email, password_hash, signup_complete) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $mysqli->stmt_init();

        if (!$stmt->prepare($sql)) {
            die("SQL error: " . $mysqli->error);
        }

        $stmt->bind_param(
            "sssssi",
            $firstname,
            $lastname,
            $username,
            $email,
            $encrypt,
            $signupComplete
        );

        $select = mysqli_query($mysqli, "SELECT * FROM all_users WHERE email = '" . $email . "'");
        if (mysqli_num_rows($select)) {
            exit('The email address is already used');
        } else {
            if ($stmt->execute()) {
                $select = sprintf("SELECT * FROM all_users
                WHERE email = '%s'",
                    $mysqli->real_escape_string($email)
                );
                $result = $mysqli->query($select);
                $user = $result->fetch_assoc();
                
                session_start();
                session_regenerate_id();
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["start"] = time();
                $_SESSION["duration"] = 604800;
                mkdir("../" . $dirName . "/" . $_SESSION["username"] . "/tracks", 0777, true);
                mkdir("../" . $dirName . "/" . $_SESSION["username"] . "/userdata", 0777, true);
                header("Refresh:1");
                echo "<label class='error-message'>You have joined successfully</label>";
                mysqli_close($mysqli);
            } else {
                if ($mysqli->errno === 1062) {
                    die("Email already taken");
                } else {
                    die($mysqli->error . " " . $mysqli->errno);
                }
            }
        }
    }
} else {
    echo "There is an error";
}

?>

<script>

    var emptyFirstname = "<?php echo $emptyFirstname; ?>";
    var emptyLastname = "<?php echo $emptyLastname; ?>";
    var emptyUsername = "<?php echo $emptyUsername; ?>";
    var emptyEmail = "<?php echo $emptyEmail; ?>";
    var emptyPassword = "<?php echo $emptyPassword; ?>";
    var emailInvalid = "<?php echo $emailInvalid; ?>"
    var passwordInvalid = "<?php echo $passwordInvalid; ?>"
    var errorMessage = document.querySelector(".error-message");

    function resetForm() {
        $("#fname, #lname, #new-username, #new-email, #new-pw").removeClass("error-field");
    }

    function loginFormSwitch() {
        resetForm();
        errorMessage.remove();
        $("#fname, #lname, #new-username, #new-email, #new-pw").val("");
    }

    resetForm();

    if (emptyFirstname == true) {
        $("#fname").addClass("error-field");
    } else if (emptyLastname == true) {
        $("#lname").addClass("error-field");
    } else if (emptyUsername == true) {
        $("#new-username").addClass("error-field");
    } else if (emptyEmail == true || emailInvalid == true) {
        $("#new-email").addClass("error-field");
    } else if (emptyPassword == true || passwordInvalid == true) {
        $("#new-pw").addClass("error-field");
    } else {
        location.reload();
    }
</script>