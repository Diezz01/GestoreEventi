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
$ora = $_POST["ora"];


if (empty($id) || empty($titolo) || empty($data) || empty($ora)) {
    echo json_encode([
        "success" => false,
        "message" => "Tutti i campi sono obbligatori"
    ]);
    exit;
}

$query = $conn -> prepare("
    SELECT stato, data, orario
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
$adesso = date("H:i");

$data_ora_evento = $data . " " . $ora; // es: 2026-01-22 14:30
$data_ora_oggi = $oggi . " " . $adesso;

if ($data_ora_evento < $data_ora_oggi) {

    echo json_encode([
        "success" => false,
        "message" => "Non puoi impostare un evento nel passato"
    ]);

    exit;
}

// UPDATE SOLO titolo e data
$query = $conn -> prepare("
    UPDATE eventi
    SET titolo = ?, data = ?, orario = ?
    WHERE id = ? AND creato_da = ?
");

$query -> bind_param("sssii", $titolo, $data, $ora, $id, $user_id);

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