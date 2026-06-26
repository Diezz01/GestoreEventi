<?php
    //import dati di accesso per il database
    require_once "config.php";

    //connessione al db
    $conn = new mysqli(HOST,
                       USER, 
                       PASSWORD,
                       NAME);

    if ($conn -> connect_error){
        die("Connessione fallita" . $conn -> connect_error);
    }

?>