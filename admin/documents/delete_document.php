<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';


// Check if document ID is present in the URL
if (!isset($_GET['id'])) {
    // Redirect to the document list page if ID is not present
    header("Location: index.php");
    die();
}

// Get the document ID from the URL
$doc_id = intval($_GET['id']);

// SQL query to retrieve the details of the selected document
$sql = "SELECT * FROM document WHERE id_document = :id";
$query = $dbh->prepare($sql);
$query->execute(['id' => $doc_id]);
$document = $query->fetch(PDO::FETCH_ASSOC);

// Check if the document exists in the database
if (!$document) {
    // Redirect to the document list page if the document doesn't exist
    header("Location: /candidat/documents/index.php");
    die();
}

// If the user confirms the deletion, delete the document from the database
if (isset($_POST['supprimer'])) {
    // SQL query to delete the document
    $sql = "DELETE FROM document WHERE id_document = :id";
    $query = $dbh->prepare($sql);
    $query->execute(['id' => $doc_id]);

    // Redirect to the document list page
    header("Location: index.php?id=" . $document['id_utilisateur']);
    die();
}
?>

<link rel="stylesheet" href="/assets/css/style_admin/style_admin_documents/style_admin_documents_delete.css">

<div class="container">
    <h1>Supprimer un document</h1>
    <br>
    <p>Voulez-vous vraiment supprimer le document "<?php echo $document['nom_document']; ?>" ?</p>
    <br>
    <form method="post">
        <input type="submit" name="supprimer" value="Supprimer">
        <button type="button" onclick="history.back()">Annuler</button>
    </form>
</div>

<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-bottom.php';
?>