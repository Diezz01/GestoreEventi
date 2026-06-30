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
<h1>Entra Qui per gestire i tuoi eventi</h1>

<form id="loginForm" action="../backend/auth/login.php" method="POST">

    <label>Username</label><br>
    <input id="username" type="text" name="username" required><br><br>
    <small id="usernameError"></small>


    <label>Password</label><br>
    <input id="password" type="password" name="password" required><br><br>
    <small id="pwdError"></small>

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

<p> Non sei ancora iscritto? Registrati <a href="registration.php">qui</a></p>



</body>
</html>