<?php
    session_start();
    require_once "../db_connection.php";

    $titolo = $_POST["titolo"];
    $data = $_POST["data"];
    $stato = $_POST["stato"];
    $creato_da = $_SESSION["user_id"];

    $query = $conn -> prepare("INSERT INTO eventi (titolo, data, stato, creato_da) VALUES (?, ?, ?, ?)");
    
    $query -> bind_param("ssii",$titolo, $data, $stato, $creato_da);

    $query -> execute();

?>