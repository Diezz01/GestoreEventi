<?php
    session_start();
    require_once "../db_connection.php";

    header('Content-Type: application/json');

    $user_id = $_SESSION["user_id"];
    $event_id = $_POST["id"];

    // prendo la data dell'evento
    $query = $conn -> prepare("SELECT data FROM eventi WHERE id = ? AND creato_da = ?");
    $query -> bind_param("ii", $event_id, $user_id);
    $query -> execute();

    $result = $query -> get_result();
    $evento = $result -> fetch_assoc();

    if (!$evento) {
        echo json_encode([
            "success" => false,
            "message" => "Evento non trovato"
        ]);
        exit;
    }

    $oggi = date("Y-m-d");

    // controllo data
    if ($evento["data"] > $oggi) {
        echo json_encode([
            "success" => false,
            "message" => "Evento ancora da fare: impossibile concludere"
        ]);
        exit;
    }

    // update stato
    $query = $conn -> prepare("
        UPDATE eventi 
        SET stato = 2 
        WHERE id = ? AND creato_da = ?
    ");

    $query -> bind_param("ii", $event_id, $user_id);
    $query -> execute();

    echo json_encode([
        "success" => true,
        "message" => "Evento concluso con successo"
    ]);
    exit;
?>