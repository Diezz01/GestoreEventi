<?php
    require_once "../db_connection.php";
    
    //prendo i dati dal form di registrazione
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $pwd = $_POST["password"];

    //hahs della password per la sicurezza
    $hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);

    $query = $conn -> prepare("INSERT INTO utenti (username, email, password) VALUES (?, ?, ?)");

    $query -> bind_param("sss", $username, $email, $hashed_pwd);

    $query -> execute();
    
    header("Location: ../../frontend/login.php");//redirect al login
    exit;

?> 