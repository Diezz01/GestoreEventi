<?php
    session_start();
    require_once "../db_connection.php";

    header('Content-Type: application/json');

    $user_id = $_SESSION["user_id"];

    //parametri usati per mostrare eventi in base alle preferenze dell'utente
    $sort = $_GET["sort"] ?? null;
    $filter = $_GET["filter"] ?? null;
    $from = $_GET["from"] ?? null;
    $to = $_GET["to"] ?? null;

    //query usata per effettuare il fetch degli eventi
    $query_base = "SELECT e.id, e.titolo, e.data, s.stato FROM eventi e INNER JOIN stati_eventi s ON s.id = e.stato WHERE e.creato_da = ?";
    
    //lista dei possibili parametri da inserire nei placeholder della query
    $params = []; 
    $types = "i";
    $params[] = $user_id;

    // filtro stato
    if ($filter == "conclusi") {
        $query_base .= " AND e.stato = 2";
    }
    if ($filter == "programmati") {
        $query_base .= " AND e.stato = 1";
    }

    // filtro range tra due date
    if ($from && $to) {
        $query_base .= " AND e.data BETWEEN ? AND ?";
        $types .= "ss";
        $params[] = $from;
        $params[] = $to;
    }

    // ordinamento
    if ($sort == "data") {
        $query_base .= " ORDER BY e.data ASC";
    } elseif ($sort == "nome") {
        $query_base .= " ORDER BY e.titolo ASC";
    } else {
        $query_base .= " ORDER BY e.id DESC";
    }

    $query = $conn -> prepare($query_base);
    
    //binding dinami dei prametri in base alla selezione dell utente
    $query -> bind_param($types, ...$params);
    $query -> execute();

    $result =$query -> get_result();
    
    $events = [];
    
    while($row = $result->fetch_assoc()){
        $events[] = $row;
    }
    
    echo json_encode($events);

?>