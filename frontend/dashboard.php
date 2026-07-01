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
    <link rel="stylesheet" href="css/stile.css">
    <script src="asset/dashboard.js"></script>

    <h1>Benvenuto <?= $_SESSION["username"] ?></h1>

    <a href="../backend/auth/logout.php" class="logout-btn">Logout</a>

    <hr>
    <div class="container">
        <h2>Crea un nuovo evento!</h2>
        <div id="Esito"></div>
        <div >
            <form id="eventForm" class=" card form-row">
                <input type="text" name="titolo" placeholder="Titolo">
                <input type="date" name="data">
                <input type="time" name="ora">

                <select name="stato">
                    <option value="1">In programma</option>
                    <option value="2">Concluso</option>
                </select>

                <button type="submit">Crea</button>
            </form>
        <div>

        <hr>

        <h2>Filtra i tuoi eventi</h2>
        <div class="card filter-card form-row">
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
        </div>
        <hr>

        <h2>Tutti i tuoi eventi</h2>
        <div id="boxEvento"></div>

        <!-- Form nascosto per la modifica di un evento -->
        <div id="modalEdit" class="modal hidden">
            <div class="modal-content">

                <h3>Modifica evento</h3>

                <input type="text" id="editTitolo" placeholder="Nuovo Titolo">
                <input type="date" id="editData" placeholder="Nuova Data">
                <input type="time" id="editOra">

                <div class="modal-actions">
                    <button onclick="salvaModifica()">Salva</button>
                    <button onclick="chiudiModifica()">Chiudi</button>
                </div>
                <br>
                <div id="modalError"></div>

            </div>
        </div>
    </div>

    <div id="toast" class="toast hidden"></div>
</body>