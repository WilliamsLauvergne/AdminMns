<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-stagiaire-check.php';

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

    // Upload du document
    $document = null;
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['pdf', 'doc', 'docx'];
        $tmp_name = $_FILES['document']['tmp_name'];
        $name = $_FILES['document']['name'];
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        if (!in_array(strtolower($extension), $allowed_types)) {
            echo "Le type de fichier n'est pas autorisé. Les types autorisés sont : " . implode(', ', $allowed_types);
            die();
        }
        $new_name = uniqid('doc_') . '.' . $extension;
        $destination = 'upload/' . $new_name;
        move_uploaded_file($tmp_name, $destination);
        $document = $new_name;
    }

    // Requête SQL pour enregistrer l'absence dans la base de données
    $sql = "INSERT INTO absences (id_utilisateur, date_debut_absence, date_fin_absence, motif, document) VALUES (:user_id, :date_debut_absence, :date_fin_absence, :motif, :document)";
    $query = $dbh->prepare($sql);
    $query->execute(['user_id' => $user_id, 'date_debut_absence' => $date_debut_absence, 'date_fin_absence' => $date_fin_absence, 'motif' => $motif, 'document' => $document]);

    // Rediriger vers la page de l'utilisateur
    header("Location: index.php?id=" . $user_id);
    die();
}
?>

<link rel="stylesheet" href="/assets/css/style_stagiaire/style_stagiaire_absences/style_stagiaire_absences_new.css">

<div>
    <h1>Ajouter une absence pour <?php echo $user['prenom_utilisateur'] . " " . $user['nom_utilisateur']; ?></h1>
    <form method="POST">
        <label for="date_debut_absence">Date de début d'absence :</label>
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