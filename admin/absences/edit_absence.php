<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-admin-check.php';


// Vérifier si l'ID de l'absence est présent dans l'URL
if (!isset($_GET['id'])) {
    // Rediriger vers la page de la liste des utilisateurs si l'ID n'est pas présent
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
    header("Location: ./index.php");
    die();
}

// Si le formulaire est soumis
if (isset($_POST['submit'])) {
    // Récupérer les données du formulaire
    $date_debut_absence = $_POST['date_debut_absence'];
    $date_fin_absence = $_POST['date_fin_absence'];
    $motif = $_POST['motif'];
    $document = $_POST['document'];

    // Requête SQL pour mettre à jour l'absence dans la base de données
    $sql = "UPDATE absences SET date_debut_absence = :date_debut_absence, date_fin_absence = :date_fin_absence, motif = :motif, document = :document WHERE id_absence = :id";
    $query = $dbh->prepare($sql);
    $query->execute(['date_debut_absence' => $date_debut_absence, 'date_fin_absence' => $date_fin_absence, 'motif' => $motif, 'id' => $absence_id, 'document' => $document]);

    // Rediriger vers la page des absences de l'utilisateur
    header("Location: index.php?id=" . $absence['id_utilisateur']);
    die();
}
?>

<link rel="stylesheet" href="/assets/css/style_admin/style_admin_absences/style_admin_absences_edit.css">

<div class="container">
    <h1>Modifier l'absence du <?php echo $absence['date_debut_absence']; ?></h1>
    <br>
    <button class="return" onclick="history.back()">Retour à la liste des absences</button>
    <br>
    <br>

    <form method="post" enctype="multipart/form-data">
        <label for="date_debut_absence">Date de début d'absence :</label>
        <input type="date" id="date_debut_absence" name="date_debut_absence" value="<?php echo $absence['date_debut_absence']; ?>" required>
        <br>
        <label for="date_fin_absence">Date de fin d'absence :</label>
        <input type="date" id="date_fin_absence" name="date_fin_absence" value="<?php echo $absence['date_fin_absence']; ?>" required>
        <br>
        <label for="motif">Motif :</label>
        <input type="text" id="motif" name="motif" value="<?php echo $absence['motif']; ?>" required>
        <br>
        <label for="document">Document :</label>
        <input type="file" id="document" name="document" value="<?php echo $absence['document']; ?>">
        <br>
        <input type="submit" name="submit" value="Modifier">
    </form>
</div>

<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-bottom.php';
?>