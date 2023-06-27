<?php
session_start(); // Démarrer une session PHP

// Détruire toutes les variables de session
$_SESSION = array();

// Effacer le cookie de session
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Détruire la session
session_destroy();

// Rediriger l'utilisateur vers la page d'accueil ou toute autre page de votre choix
header('Location: /index.php');
exit;
