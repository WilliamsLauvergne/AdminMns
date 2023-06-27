<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-admin-check.php';

// Vérifier si l'ID du retard est présent dans l'URL
if (!isset($_GET['id'])) {
    // Rediriger vers la page de la liste des utilisateurs si l'ID n'est pas présent
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
    // Rediriger vers la page de la liste des retards si le retard n'existe pas
    header("Location: ./index.php");
    die();
}

// Si le formulaire est soumis
if (isset($_POST['submit'])) {
    // Récupérer les données du formulaire
    $date_retard = $_POST['date_retard'];
    $motif = $_POST['motif_retard'];

    // Requête SQL pour mettre à jour le retard dans la base de données
    $sql = "UPDATE retards SET date_retard = :date_retard, motif_retard = :motif_retard WHERE id_retard = :id";
    $query = $dbh->prepare($sql);
    $query->execute(['date_retard' => $date_retard, 'motif_retard' => $motif, 'id' => $retard_id]);

    // Rediriger vers la page des retards de l'utilisateur
    header("Location: index.php?id=" . $retard['id_utilisateur']);
    die();
}
?>

<link rel="stylesheet" href="/assets/css/style_admin/style_admin_retards/style_admin_retards_edit.css">

<div class="container">
    <h1>Modifier le retard du <?php echo $retard['date_retard']; ?></h1>
    <br>
    <button class="return" onclick="history.back ()">Retour à la liste des retards</button>
    <br>
    <br>
    <form method="post">
        <label for="date_debut_absence">Date du retard :</label>
        <input type="datetime-local" id="date_retard" name="date_retard" value="<?php echo $retard['date_retard']; ?>" required>
        <br>
        <label for="motif_retard">Motif :</label>
        <input type="text" id="motif_retard" name="motif_retard" value="<?php echo $retard['motif_retard']; ?>" required>
        <br>
        <input type="submit" name="submit" value="Modifier">
    </form>

</div>

<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-bottom.php';
?>