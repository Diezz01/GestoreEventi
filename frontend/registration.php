<?php
    $error = "";

    if (isset($_GET["error"])) {

        switch ($_GET["error"]) {

            case "username_exists":
                $error = "Username già utilizzato";
                break;

            case "email_exists":
                $error = "Email già utilizzata";
                break;
        }

    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>SignUP</title>
</head>
<body>
<script src="asset/singup_validation.js"></script>
<h1>Registrati Qui per gestire i tuoi eventi</h1>

<form id="signUp_form" action="../backend/auth/register.php" method="POST">

    <label>Username</label><br>
    <input id="username" type="text" name="username" required><br><br>
    <small id="usernameError"></small>

    <label>Email</label><br>
    <input id="email" type="email" name="email" required><br><br>
    <small id="emailError"></small>

    <label>Password</label><br>
    <input id="password" type="password" name="password" required><br><br>
    <small id="pwdError"></small>

    <?php if ($error != ""): ?>
        <p class="error">
            <?= $error ?>
        </p>
    <?php endif; ?>

    <button id="submit_button" type="submit" disabled>
        Registrati
    </button>

</form>

</body>
</html>