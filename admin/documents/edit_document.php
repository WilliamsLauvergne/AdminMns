<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';


// Vérifier si l'ID du document est présent dans l'URL
if (!isset($_GET['id'])) {
    // Rediriger vers la page de la liste des documents si l'ID n'est pas présent
    header("Location: index.php");
    die();
}

// Récupérer l'ID du document à partir de l'URL
$document_id = intval($_GET['id']);

// Requête SQL pour récupérer les informations du document sélectionné
$sql = "SELECT * FROM document WHERE id_document = :id";
$query = $dbh->prepare($sql);
$query->execute(['id' => $document_id]);
$document = $query->fetch(PDO::FETCH_ASSOC);

// Vérifier si le document existe dans la base de données
if (!$document) {
    // Rediriger vers la page de la liste des documents si le document n'existe pas
    header("Location: index.php");
    die();
}

// Récupérer les informations de l'utilisateur associé au document
$sql = "SELECT * FROM utilisateur WHERE id_utilisateur = :id";
$query = $dbh->prepare($sql);
$query->execute(['id' => $document['id_utilisateur']]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur existe dans la base de données
if (!$user) {
    // Rediriger vers la page de la liste des documents si l'utilisateur n'existe pas
    header("Location: index.php");
    die();
}

// Traitement du formulaire de modification de document
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_document = $_POST['nom_document'];
    $erreur = false;

    // Vérification que le nom du document a été saisi
    if (empty($nom_document)) {
        $erreur = true;
        $erreur_nom_document = "Le nom du document est obligatoire.";
    }

    // Si le formulaire est valide, mise à jour du document dans la base de données
    if (!$erreur) {
        $sql = "UPDATE document SET nom_document = :nom_document WHERE id_document = :id";
        $query = $dbh->prepare($sql);
        $query->execute(['nom_document' => $nom_document, 'id' => $document_id]);

        // Redirection vers la page de liste des documents
        header("Location: index.php");
        die();
    }
}
?>
<link rel="stylesheet" href="/assets/css/style_admin/style_admin_documents/style_admin_documents_edit.css">
<div>
    <h1>Modifier le document de <?php echo $user['prenom_utilisateur'] . " " . $user['nom_utilisateur']; ?></h1>
    <br>
    <div class="btn-group">
        <button type="button" onclick="history.back()">Retour à la liste des documents</button>
    </div>
    <br>
    <br>
    <form method="POST">
        <div>
            <label for="nom_document">Nom du document :</label>
            <input type="text" name="nom_document" id="nom_document" value="<?php echo htmlspecialchars($document['nom_document']); ?>">
            <?php if (isset($erreur_nom_document)) : ?>
                <p class="erreur"><?php echo $erreur_nom_document; ?></p>
            <?php endif; ?>
        </div