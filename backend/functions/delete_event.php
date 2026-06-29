<?php
    session_start();
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }

    header('Content-Type: application/json');
    require_once "../db_connection.php";

    $user_id = $_SESSION["user_id"];
    $event_id = $_POST["id"];

    $query = $conn -> prepare("DELETE FROM eventi WHERE id = ? AND creato_da = ?");

    if(!$event_id){
        echo json_encode([
            "success" => false,
            "message" => "ID non valido"
        ]);
        exit;
    }
    
    $query -> bind_param("ii",$event_id , $user_id);

    $query -> execute();

    if($query -> affected_rows > 0){

        echo json_encode([
            "success" => true,
            "message" => "Evento eliminato con successo"
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "message" => "Errore durante l'eliminazione dell'evento"
        ]);
    }

    exit;
?>