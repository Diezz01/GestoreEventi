<?php
    session_start();
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <script src="asset/dashboard.js"></script>
    <h1>Benvenuto <?= $_SESSION["username"] ?></h1>

    <a href="../backend/auth/logout.php">Logout</a>

    <hr>

    <h2>Crea evento</h2>
    <div id="Esito"></div>
    <form id="eventForm">
        <input type="text" name="titolo" placeholder="Titolo">
        <input type="date" name="data">

        <select name="stato">
            <option value="1">In programma</option>
            <option value="2">Concluso</option>
        </select>

        <button type="submit">Crea</button>
    </form>

    <hr>

    <h2>I tuoi eventi</h2>
    <select id="sort">
        <option value="">Ordina</option>
        <option value="data">Data</option>
        <option value="nome">Nome</option>
    </select>

    <select id="filter">
        <option value="">Tutti</option>
        <option value="programmati">In programma</option>
        <option value="conclusi">Conclusi</option>
    </select>

    <input type="date" id="from">
    <input type="date" id="to">

    <button id="applyFilters">Filtra</button>

    <div id="boxEvento"></div>
</body>