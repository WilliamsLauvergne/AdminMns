<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';


// Vérifier si l'utilisateur est connecté
if (!isset($_GET['id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: index.php");
    die();
}

// Récupérer l'ID de l'utilisateur à partir de l'URL
$id = intval($_GET['id']);

// Traitement du formulaire d'ajout de document
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier les données du formulaire (vous pouvez ajouter des validations supplémentaires ici)
    $nomDocument = $_POST['nom_document'];
    $dateCreation = date('Y-m-d'); // Date de création actuelle

    if (isset($_FILES['document']['name']) && $_FILES['document']['name'] !== '') {
        $nomDocument = $_FILES['document']['name'];
        $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/upload/" . $nomDocument;
        move_uploaded_file($_FILES['document']['tmp_name'], $targetPath);
    } else {
        // Afficher un message d'erreur ou rediriger l'utilisateur vers une autre page
        exit("Le nom du document est manquant ou n'a pas été sélectionné.");
    }
    // Requête SQL pour ajouter un nouveau document
    $sql = "INSERT INTO document (id_utilisateur, nom_document, date_creation) VALUES (:id_utilisateur, :nom_document, :date_creation)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id_utilisateur', $id, PDO::PARAM_INT);
    $query->bindParam(':nom_document', $nomDocument, PDO::PARAM_STR);
    $query->bindParam(':date_creation', $dateCreation, PDO::PARAM_STR);
    $query->execute();

    // Enregistrer le fichier uploadé dans le dossier "upload"
    $target_dir = "upload/";
    $target_file = $target_dir . basename($_FILES["document"]["name"]);
    move_uploaded_file($_FILES["document"]["tmp_name"], $target_file);

    // Rediriger vers la page de liste des documents après l'ajout du document
    header("Location: index.php?id=" . $id);
    die();
}
?>

<style>
    div {
        margin: 1%;
    }

    h1 {
        text-transform: uppercase;
        text-align: center;
    }

    form {
        border: 5px solid rgb(47, 60, 110);
        padding: 2%;
        width: 50%;
    }

    label {
        display: block;
        margin-bottom: 10px;
    }

    input[type="date"],
    textarea {
        width: 100%;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .validate_btn,
    .return_btn {
        padding: 10px 20px;
        border-radius: 20px;
        text-transform: uppercase;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .validate_btn {
        background-color: white;
        color: black;
        margin-right: 10px;
    }

    .return_btn {
        background-color: white;
        color: black;
    }

    .validate_btn:hover {
        background-color: green;
        color: white;
    }

    .return_btn:hover {
        background-color: red;
        color: white;
    }
</style>
<div>
    <h1>Ajouter un document</h1>
    <br>
    <form method="post" enctype="multipart/form-data">
        <label for="nom_document">Nom du document:</label>
        <input type="text" id="document" name="nom_document" required>
        <br>
        <label for="document">Document:</label>
        <input type="file" id="document" name="document" required>
        <br>
        <input class="validate_btn" type="submit" value="Ajouter">
    </form>
    <br>
    <button class="return_btn" onclick="history.back()">Annuler</button>
</div>