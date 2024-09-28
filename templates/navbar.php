<header>
    <div class="logo"><a class="nav-link" href="/"></a></div>
    <div class="navbtns">
        <?php if (isset($user)): ?>
            <!-- <a class="nav-link" href="/beats">
                Beats
            </a>
            <a class="nav-link" href="/resources">
                Resources
            </a> -->
            <div class="nav-link-dropdown">
                <!-- <a class="nav-link" id="account-nav-link1" href="/user?user=<?= $_SESSION["username"] ?>"> -->
                <a id="account-nav-link1">
                    <?= $_SESSION["username"] ?>

                    <?php
                    
                    $mysqli = require "./db/database.php";

                    $sql = "SELECT profile_pic_url FROM user_data WHERE id = {$_SESSION["user_id"]}";

                    $result = mysqli_query($mysqli, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<img id='user-pp-header' src='db/" . substr($row["profile_pic_url"], 3) . "'>";
                        }
                    }
                    ?>
                </a>
                <a class="nav-link" id="account-nav-link2" href="/user?user=<?= $_SESSION["username"] ?>">
                    <div class="icon-mask" id="account-btn" draggable="false"></div>
                    Account
                </a>
                <a class="nav-link" id="account-nav-link3" href="/account">
                    <div class="icon-mask" id="vault-btn" draggable="false"></div>
                    My Vault
                </a>
                <a class="nav-link" id="account-nav-link4" href="/settings">
                    <div class="icon-mask" id="settings-btn" src="css/misc/img/black-icons/settings.png" draggable="false"></div>
                    Settings
                </a>
                <a href="/logout" class="nav-link" id="account-nav-link5">
                    <div class="icon-mask" id="logout-btn" src="css/misc/img/black-icons/logout.png" draggable="false"></div>
                    Log Out
                </a>
            </div>
        <?php elseif ($_SERVER['REQUEST_URI'] == '/'): ?>
            <a class="nav-link" href="/login">
                Log In
            </a>
        <?php elseif (isset($_SESSION["signup-incomplete"])): ?>
            <a href="/logout" class="nav-link" style="gap: 10px;">
                Log Out
                <div class="icon-mask" id="logout-btn" src="css/misc/img/black-icons/logout.png" draggable="false"></div>
            </a>
        <?php endif; ?>
    </div>
</header>