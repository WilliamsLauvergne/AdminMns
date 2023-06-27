<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'stagiaire') {
    // Redirigez l'utilisateur vers une autre page appropriée, par exemple, la page de connexion
    header('Location: /index.php');
    exit();
}
