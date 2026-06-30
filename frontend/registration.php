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
    <link rel="stylesheet" href="css/auth.css">
    <script src="asset/singup_validation.js"></script>
    <div class="auth-container">
        <h1>Registrati su <br> Event Manager</h1>
        
        <form id="signUp_form" action="../backend/auth/register.php" method="POST">

            <div class="field">
                <label>Username</label>
                <small id="usernameError"></small>
            </div>
            <input id="username" type="text" name="username" required><br><br>
            

            <div class="field">
                <label>Email</label>
                <small id="emailError"></small>
            </div>
            <input id="email" type="email" name="email" required><br><br>
            <div class="field">
                <label>Password</label>
                <small id="pwdError"></small>
            </div>
            <input id="password" type="password" name="password" required><br><br>


            <?php if ($error != ""): ?>
                <p class="error">
                    <?= $error ?>
                </p>
            <?php endif; ?>

            <button id="submit_button" type="submit" disabled>
                Registrati
            </button>

        </form>
    </div>
</body>
</html>