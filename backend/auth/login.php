<?php 
    session_start();

    require_once "../db_connection.php";

    //prendo i dati dal form di login
    $username = trim($_POST["username"]);
    $pwd = $_POST["password"];

    $query = $conn->prepare("SELECT id, username, password FROM utenti WHERE username = ?");

    $query -> bind_param("s", $username);
    $query -> execute();

    $result = $query -> get_result();
    $user = $result -> fetch_assoc(); 

    //verifico la validità dell'utente
    if($user && password_verify($pwd, $user["password"])){
        //se l'utente è verificato creo la sua sessione
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];

        header("Location: ../../frontend/dashboard.php");
        exit;

    }else{
        $_SESSION["login_error"] = "Errore durante l'accesso! Credenziali NON valide!";
        header("Location: ../../frontend/login.php");
        exit;
    }


?>