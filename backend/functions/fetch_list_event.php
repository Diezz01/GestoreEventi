<?php
    require_once "../db_connection.php";

    header('Content-Type: application/json');
    
    //query usata per effettuare il fetch degli eventi
    $query = "SELECT e.titolo, e.data, stati_eventi.stato
              FROM eventi AS e INNER JOIN stati_eventi ON e.stato = stati_eventi.id";
    
    $result = $conn->query($query);
    
    $events = [];
    
    while($row = $result->fetch_assoc()){
        $events[] = $row;
    }
    
    echo json_encode($events);

?>