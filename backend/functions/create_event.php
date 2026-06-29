<?php
    session_start();
    require_once "../db_connection.php";
    header('Content-Type: application/json');

    $titolo = $_POST["titolo"];
    $data = $_POST["data"];
    $stato = $_POST["stato"];
    $creato_da = $_SESSION["user_id"];

    //prima di inserire un evento verifico la consistenza tra data e stato
    $oggi = date("Y-m-d");

    if ($stato == 2 && $data > $oggi) {

        echo json_encode([
            "success" => false,
            "message" => "Non puoi creare un evento concluso con una data futura"
        ]);

        exit;
    }

    $query = $conn -> prepare("INSERT INTO eventi (titolo, data, stato, creato_da) VALUES (?, ?, ?, ?)");
    
    $query -> bind_param("ssii",$titolo, $data, $stato, $creato_da);

    $query -> execute();
    
    echo json_encode([
        "success" => true,
        "message" => "Evento registrato con successo"
    ]);

    exit;
?>