<?php

    session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <script src="asset/signin_validation.js" ></script>
    <link rel="stylesheet" href="css/auth.css">
    <div class="auth-container">
        <h1>Event Manager</h1>

        <?php if(isset($_SESSION["register_success"])): ?>
            <div class="success-message">
                <?= $_SESSION["register_success"] ?>
            </div>
            <?php unset($_SESSION["register_success"]); ?>
        <?php endif; ?>

        <form id="loginForm" action="../backend/auth/login.php" method="POST">

            <div class="field">
                <label>Username</label>
                <small id="usernameError"></small>
            </div>
            <input id="username" type="text" name="username" required><br><br>
            

            <div class="field">
                <label>Password</label>
                <small id="pwdError"></small>
            </div>
            <input id="password" type="password" name="password" required><br><br>
            

            <button id="submit_button" type="submit" disabled>Accedi</button><br><br>
            <?php
                if(isset($_SESSION["login_error"])){

                    echo "
                    <small style='color:red'>
                        {$_SESSION["login_error"]}
                    </small>
                    ";

                    unset($_SESSION["login_error"]);
                }
            ?>
        </form>

        <p> Non sei ancora iscritto? Registrati <a href="registration.php">qui</a>!</p>
    </div>


    </body>
</html>