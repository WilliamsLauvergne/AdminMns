<?php

require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';

if (isset($_GET['id'])) {
    $absence_id = intval($_GET['id']);

    // Requête SQL pour récupérer les informations de l'absence sélectionnée
    $sql = "SELECT * FROM absences WHERE id_absence = :absence_id";
    $query = $dbh->prepare($sql);
    $query->execute(['absence_id' => $absence_id]);
    $absence = $query->fetch(PDO::FETCH_ASSOC);

    if (!$absence) {
        // Rediriger vers la page d'erreur si l'absence n'existe pas
        header("Location: /404.php");
        die();
    }

    $file_path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/" . $absence['document'];

    if (file_exists($file_path)) {
        // Définir les en-têtes pour déclencher le téléchargement
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));

        // Lire le fichier et l'envoyer à la sortie
        readfile($file_path);
        exit;
    } else {
        // Rediriger vers la page d'erreur si le fichier n'existe pas
        header("Location: /404.php");
        die();
    }
} else {
    // Rediriger vers la page d'erreur si l'ID de l'absence n'est pas présent dans l'URL
    header("Location: /404.php");
    die();
}
