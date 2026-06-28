<?php
    session_start();
    require_once "../db_connection.php";

    header('Content-Type: application/json');

    $user_id = $_SESSION["user_id"];
    
    //query usata per effettuare il fetch degli eventi
    $query = $conn -> prepare("SELECT e.id, e.titolo, e.data, s.stato FROM eventi e INNER JOIN stati_eventi s ON s.id = e.stato WHERE e.creato_da = ?");
    
    $query -> bind_param("i", $user_id);
    $query -> execute();

    $result =$query -> get_result();
    
    $events = [];
    
    while($row = $result->fetch_assoc()){
        $events[] = $row;
    }
    
    echo json_encode($events);

?>