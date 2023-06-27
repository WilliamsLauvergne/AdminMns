<?php

require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-admin-check.php';


// Vérifier si l'ID de l'utilisateur est présent dans l'URL
if (!isset($_GET['id'])) {
    // Rediriger vers la page de la liste des utilisateurs si l'ID n'est pas présent
    header("Location: index.php");
    die();
}

// Récupérer l'ID de l'utilisateur à partir de l'URL
$id = intval($_GET['id']);

// Requête SQL pour récupérer les informations de l'utilisateur sélectionné
$sql = "SELECT * FROM utilisateur WHERE id_utilisateur = :id";
$query = $dbh->prepare($sql);
$query->execute(['id' => $id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur existe dans la base de données
if (!$user) {
    // Rediriger vers la page de la liste des utilisateurs si l'utilisateur n'existe pas
    header("Location: index.php");
    die();
}

// Si l'utilisateur confirme la suppression, supprimer l'utilisateur de la base de données
if (isset($_POST['supprimer'])) {
    // Requête SQL pour supprimer l'utilisateur de la base de données
    $sql = "DELETE FROM utilisateur WHERE id_utilisateur = :id";
    $query = $dbh->prepare($sql);
    $query->execute(['id' => $id]);

    // Rediriger vers la page de la liste des utilisateurs après la suppression
    header("Location: index.php");
    die();
}
?>
<link rel="stylesheet" href="/assets/css/style_admin/style_admin_delete-user.css">

<div class="container">
    <h1>Supprimer l'utilisateur "<?php echo $user['prenom_utilisateur'] . " " . $user['nom_utilisateur']; ?>"</h1>

    <p>Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.</p>

    <form method="post">
        <input type="submit" name="supprimer" value="supprimer">
        <button type="button" onclick="history.back()">Annuler</button>
    </form>
</div>