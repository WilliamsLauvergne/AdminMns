<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-stagiaire-check.php';

// Vérifier si l'ID de l'absence est présent dans l'URL
if (!isset($_GET['id'])) {
    // Rediriger vers la page de la liste des absences si l'ID n'est pas présent
    header("Location: index.php");
    die();
}

// Récupérer l'ID de l'absence à partir de l'URL
$absence_id = intval($_GET['id']);

// Requête SQL pour récupérer les informations de l'absence sélectionnée
$sql = "SELECT * FROM absences WHERE id_absence = :id";
$query = $dbh->prepare($sql);
$query->execute(['id' => $absence_id]);
$absence = $query->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'absence existe dans la base de données
if (!$absence) {
    // Rediriger vers la page de la liste des absences si l'absence n'existe pas
    header("Location: /admin/absences/index.php");
    die();
}

// Si l'utilisateur confirme la suppression, supprimer l'utilisateur de la base de données
if (isset($_POST['supprimer'])) {
    // Requête SQL pour supprimer l'absence
    $sql = "DELETE FROM absences WHERE id_absence = :id";
    $query = $dbh->prepare($sql);
    $query->execute(['id' => $absence_id]);

    // Rediriger vers la page de la liste des absences
    header("Location: index.php?id=" . $absence['id_utilisateur']);
    die();
}
?>

<link rel="stylesheet" href="/assets/css/style_stagiaire/style_stagiaire_absences/style_stagiaire_absences_delete.css">

<div class="container">
    <h1>Supprimer une absence</h1>
    <br>
    <p>Voulez-vous vraiment supprimer l'absence du <?php echo $absence['date_debut_absence']; ?> au <?php echo $absence['date_fin_absence']; ?> pour le motif "<?php echo $absence['motif']; ?>" ?</p>
    <br>
    <form method="post">
        <input type="submit" name="supprimer" value="Supprimer">
        <button type="button" onclick="history.back()">Annuler</button>
    </form>
</div>