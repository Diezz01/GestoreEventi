<?php

session_start();
require_once "../db_connection.php";

header("Content-Type: application/json");


if (!isset($_SESSION["user_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Non autorizzato"
    ]);
    exit;
}

$user_id = $_SESSION["user_id"];

$id = $_POST["id"];
$titolo = trim($_POST["titolo"]);
$data = $_POST["data"];


$query = $conn -> prepare("
    SELECT stato, data
    FROM eventi
    WHERE id = ? AND creato_da = ?
");

$query -> bind_param("ii", $id, $user_id);
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


if ((int)$evento["stato"] !== 1) {
    echo json_encode([
        "success" => false,
        "message" => "Puoi modificare solo eventi in programma"
    ]);
    exit;
}

$oggi = date("Y-m-d");

if ($data < $oggi) {
    echo json_encode([
        "success" => false,
        "message" => "Evento in programma! Inserire una data successiva a quella odierna"
    ]);
    exit;
}


// UPDATE SOLO titolo e data
$query = $conn -> prepare("
    UPDATE eventi
    SET titolo = ?, data = ?
    WHERE id = ? AND creato_da = ?
");

$query -> bind_param("ssii", $titolo, $data, $id, $user_id);

if ($query -> execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Evento modificato con successo"
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Errore durante la modifica"
    ]);
}

exit;

?>