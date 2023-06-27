<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-admin-check.php';

// Vérifier si l'ID de l'absence est présent dans l'URL
if (!isset($_GET['id'])) {
    // Rediriger vers la page de la liste des retards si l'ID n'est pas présent
    header("Location: index.php");
    die();
}

// Récupérer l'ID du retard à partir de l'URL
$retard_id = intval($_GET['id']);

// Requête SQL pour récupérer les informations de l'absence sélectionnée
$sql = "SELECT * FROM retards WHERE id_retard = :id";
$query = $dbh->prepare($sql);
$query->execute(['id' => $retard_id]);
$retard = $query->fetch(PDO::FETCH_ASSOC);

// Vérifier si le retard existe dans la base de données
if (!$retard) {
    // Rediriger vers la page de la liste des retard si le retard n'existe pas
    header("Location: /admin/retards/");
    die();
}

// Si l'utilisateur confirme la suppression, supprimer le retard de la base de données
if (isset($_POST['supprimer'])) {
    // Requête SQL pour supprimer l'absence
    $sql = "DELETE FROM retards WHERE id_retard = :id";
    $query = $dbh->prepare($sql);
    $query->execute(['id' => $retard_id]);

    // Rediriger vers la page de la liste des retards
    header("Location: index.php?id=" . $retard['id_utilisateur']);
    die();
}
?>

<link rel="stylesheet" href="/assets/css/style_admin/style_admin_retards/style_admin_retards_delete.css">

<div class="container">
    <h1>Supprimer un retard</h1>
    <br>
    <p>Voulez-vous vraiment supprimer le retard du <?php echo $retard['date_retard']; ?> pour le motif "<?php echo $retard['motif_retard']; ?>" ?</p>
    <br>
    <form method="post">
        <input type="submit" name="supprimer" value="supprimer">
        <button type="button" onclick="history.back()">Annuler</button>
    </form>
</div>

<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-bottom.php';
?>