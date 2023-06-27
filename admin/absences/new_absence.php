<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-admin-check.php';


// Vérifier si l'ID de l'utilisateur est présent dans l'URL
if (!isset($_GET['id'])) {
    // Rediriger vers la page de la liste des utilisateurs si l'ID n'est pas présent
    header("Location: view_user.php");
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
    $date_debut_absence = $_POST['date_debut_absence'];
    $date_fin_absence = $_POST['date_fin_absence'];
    $motif = $_POST['motif'];
    $document = $_POST['document'];

    // Vérifier si un fichier a été téléchargé
    if (!empty($_FILES['document']['name'])) {
        // Nom du fichier téléchargé
        $file_name = $_FILES['document']['name'];
        // Emplacement temporaire du fichier téléchargé
        $file_tmp = $_FILES['document']['tmp_name'];

        // Déplacer le fichier téléchargé vers l'emplacement souhaité
        $destination = $_SERVER['DOCUMENT_ROOT'] . '/upload/' . $file_name;
        if (move_uploaded_file($file_tmp, $destination)) {
            // Requête SQL pour enregistrer l'absence dans la base de données avec le nom du fichier
            $sql = "INSERT INTO absences (id_utilisateur, date_debut_absence, date_fin_absence, motif, document) VALUES (:user_id, :date_debut_absence, :date_fin_absence, :motif, :document)";
            $query = $dbh->prepare($sql);
            $query->execute(['user_id' => $user_id, 'date_debut_absence' => $date_debut_absence, 'date_fin_absence' => $date_fin_absence, 'motif' => $motif, 'document' => $file_name]);
        } else {
            // Gérer les erreurs de téléchargement de fichier
            echo "Erreur lors du téléchargement du fichier.";
        }
    } else {
        // Requête SQL pour enregistrer l'absence dans la base de données sans fichier
        $sql = "INSERT INTO absences (id_utilisateur, date_debut_absence, date_fin_absence, motif) VALUES (:user_id, :date_debut_absence, :date_fin_absence, :motif)";
        $query = $dbh->prepare($sql);
        $query->execute(['user_id' => $user_id, 'date_debut_absence' => $date_debut_absence, 'date_fin_absence' => $date_fin_absence, 'motif' => $motif]);
    }

    // Rediriger vers la page de l'utilisateur
    header("Location: index.php?id=" . $user_id);
    die();
}
?>

<link rel="stylesheet" href="/assets/css/style_admin/style_admin_absences/style_admin_absences_new.css">

<div>
    <h1>Ajouter une absence pour <?php echo $user['prenom_utilisateur'] . " " . $user['nom_utilisateur']; ?></h1>
    <form method="POST" enctype="multipart/form-data">
        <label for=" date_debut_absence">Date de début d'absence :</label>
        <input type="date" id="date_debut_absence" name="date_debut_absence" required><br><br>
        <label for="date_fin_absence">Date de fin d'absence :</label>
        <input type="date" id="date_fin_absence" name="date_fin_absence" required><br><br>
        <label for="motif">motif :</label>
        <textarea id="motif" name="motif" required></textarea><br><br>
        <label for="document">Document :</label>
        <input type="file" id="document" name="document"><br><br>
        <br>
        <input class="validate_btn" type="submit" name="submit" value="Enregistrer">
    </form>
    <br>
    <button class="return_btn" onclick="history.back()">Annuler</button>
</div>

<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-bottom.php';
?>