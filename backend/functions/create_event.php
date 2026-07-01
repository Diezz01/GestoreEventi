<?php
    session_start();
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }
    
    require_once "../db_connection.php";
    header('Content-Type: application/json');

    $titolo = $_POST["titolo"];
    $data = $_POST["data"];
    $stato = $_POST["stato"];
    $ora = $_POST["ora"];
    $creato_da = $_SESSION["user_id"];

    if (empty($titolo) || empty($data) || empty($stato) || empty($ora)) {
        echo json_encode([
            "success" => false,
            "message" => "Tutti i campi sono obbligatori"
        ]);
        exit;
    }

    //prima di inserire un evento verifico la consistenza tra data e stato
    $adesso = date("Y-m-d H:i");

    $dataEvento = $data . " " . $ora;

    if ($stato == 2 && $dataEvento > $adesso) {

        echo json_encode([
            "success" => false,
            "message" => "Non puoi creare un evento concluso con data e ora future"
        ]);

        exit;
    }

    if ($stato == 1 && $data < $adesso) {

        echo json_encode([
            "success" => false,
            "message" => "Un evento passato non può essere messo in programma"
        ]);

        exit;
    }

    $query = $conn -> prepare("INSERT INTO eventi (titolo, data, orario, stato, creato_da) VALUES (?, ?, ?, ?, ?)");
    
    $query -> bind_param("sssii",$titolo, $data, $ora, $stato, $creato_da);

    $query -> execute();
    
    echo json_encode([
        "success" => true,
        "message" => "Evento registrato con successo"
    ]);

    exit;
?>