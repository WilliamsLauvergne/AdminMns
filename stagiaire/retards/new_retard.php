<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-stagiaire-check.php';

// Vérifier si l'ID de l'utilisateur est présent dans l'URL
if (!isset($_GET['id'])) {
    // Rediriger vers la page de la liste des utilisateurs si l'ID n'est pas présent
    header("Location: index.php");
    die();
}

// Récupérer l'ID de l'utilisateur à partir de l'URL
$user_id = intval($_GET['id']);

// Requête SQL pour récupérer les informations de l'utilisateur sélectionné
$sql = "SELECT * FROM utilisateur WHERE id_utilisateur = :id";
$query = $dbh->prepare($sql);
$query->execute(['id' => $user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur existe dans la base de données
if (!$user) {
    // Rediriger vers la page de la liste des utilisateurs si l'utilisateur n'existe pas
    header("Location: index.php");
    die();
}


// Traitement du formulaire d'ajout d'absence
if (isset($_POST['submit'])) {
    // Récupérer les données du formulaire
    $date_retard = $_POST['date_retard'];
    $motif_retard = $_POST['motif_retard'];

    // Requête SQL pour enregistrer l'absence dans la base de données
    $sql = "INSERT INTO retards (id_utilisateur, date_retard, motif_retard) VALUES (:user_id, :date_retard, :motif_retard)";
    $query = $dbh->prepare($sql);
    $query->execute(['user_id' => $user_id, 'date_retard' => $date_retard, 'motif_retard' => $motif_retard]);

    // Rediriger vers la page de l'utilisateur
    header("Location: index.php?id=" . $user['id_utilisateur']);
    die();
}
?>

<link rel="stylesheet" href="/assets/css/style_stagiaire/style_stagiaire_retards/style_stagiaire_retards_new.css">

<div>
    <h1>Ajouter un retard pour <?php echo $user['prenom_utilisateur'] . " " . $user['nom_utilisateur']; ?></h1>
    <form method="POST">
        <label for="date_retard">Date de retard :</label>
        <input type="datetime-local" id="date_retard" name="date_retard" required><br><br>
        <label for="motif_retard">motif :</label>
        <textarea id="motif_retard" name="motif_retard" required></textarea>
        <br>
        <input class="validate_btn" type="submit" name="submit" value="Enregistrer">
    </form>
    <br>
    <button class="return_btn" onclick="history.back ()">Annuler</button>
</div>