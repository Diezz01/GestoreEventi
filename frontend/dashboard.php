<?php
    session_start();
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }
?>
<h1>Benvenuto <?= $_SESSION["username"] ?></h1>

<a href="../backend/auth/logout.php">Logout</a>

<hr>

<h2>Crea evento</h2>

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

<div id="eventsContainer"></div>