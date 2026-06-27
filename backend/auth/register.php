<?php
    require_once "../db_connection.php";
    
    //prendo i dati dal form di registrazione
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $pwd = $_POST["password"];

    //prima di inserire il nuovo utente controllo se ci sono duplicati in email o username
    $query = $conn -> prepare("SELECT * FROM utenti WHERE username = ? OR email = ?");

    $query -> bind_param("ss", $username, $email);

    $query -> execute();

    $result = $query -> get_result();

    //se un utente è gia registrato con la mail o username la fase di registrazione termina e viene resttituito l'errore 
    if($result -> num_rows > 0){
        $find_user = $result -> fetch_assoc();

        if($find_user["username"] == $username){
            header("Location: ../../frontend/registration.php?error=username_exists");
            exit;
        }
        if($find_user["email"] == $email){
            header("Location: ../../frontend/registration.php?error=email_exists");
            exit;
        }
    }

    //continuo la registrazione in assenza di duplicati
    //hahs della password per la sicurezza
    $hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);

    $query = $conn -> prepare("INSERT INTO utenti (username, email, password) VALUES (?, ?, ?)");

    $query -> bind_param("sss", $username, $email, $hashed_pwd);

    $query -> execute();
    
    header("Location: ../../frontend/login.php");//redirect al login
    exit;

?> 