<?php
session_start();
if (!isset($_SESSION['user'])) {


    // Rediriger vers la page de connexion si l'utilisateur n'est pas authentifié


    header("Location: /index.php");


    exit();
}

$sql = "SELECT * FROM utilisateur";
$result = $dbh->query($sql);
