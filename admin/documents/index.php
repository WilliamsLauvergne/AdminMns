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
    header("Location: ./index.php");
    die();
}

// Requête SQL pour récupérer les documents de l'utilisateur sélectionné
$sql = "SELECT * FROM document WHERE id_utilisateur = :user_id";
$query = $dbh->prepare($sql);
$query->execute(['user_id' => $user_id]);
$documents = $query->fetchAll(PDO::FETCH_ASSOC);

// Traitement de la mise à jour de l'état du document
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['document_status']) && isset($_POST['document_id'])) {
        $documentStatus = $_POST['document_status'] ? 1 : 0;
        $documentId = intval($_POST['document_id']);

        // Mise à jour de l'état du document dans la base de données
        $updateSql = "UPDATE document SET status = :status WHERE id_document = :id";
        $updateQuery = $dbh->prepare($updateSql);
        $updateQuery->execute(['status' => $documentStatus, 'id' => $documentId]);
    }
}
?>
<link rel="stylesheet" href="/assets/css/style_admin/style_admin_documents/">
<style>
    div {
        margin: 1%;
    }

    h1 {
        text-transform: uppercase;
        text-align: center;
    }

    table {
        border-collapse: collapse;
        width: 98%;
        margin-left: 1%;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    tr:hover {
        background-color: #f5f5f51c;
    }

    th {
        background-color: rgb(255, 101, 28);
        color: white;
    }

    .modify_btn:hover {
        background-color: green;
        color: white;
    }

    .delete_btn:hover {
        background-color: red;
        color: white;
    }

    .valid_btn {
        background-color: green;
        color: white;
    }

    .invalid_btn {
        background-color: red;
        color: white;
    }
</style>
<div>
    <h1>Documents de <?php echo $user['prenom_utilisateur'] . " " . $user['nom_utilisateur']; ?></h1>
    <br>
    <div class="btn-group">
        <button onclick="location.href='../view_user.php?id=<?php echo $user_id; ?>'">Retour à la page de l'utilisateur</button>
        <button onclick="location.href='new_document.php?id=<?php echo $user_id; ?>'">Ajouter un document +</button>
    </div>
    <br>
    <br>
    <?php if (empty($documents)) : ?>
        <p>Aucun document enregistré pour cet utilisateur.</p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>Date de création</th>
                    <th>Nom du document</th>
                    <th>Actions</th>
                    <th>Validation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $document) : ?>
                    <tr>
                        <td><?php echo $document['date_creation']; ?></td>
                        <td><?php echo $document['nom_document']; ?></td>
                        <td>
                            <a href="<?php echo '/upload/' . $document['nom_document']; ?>" download>Télécharger</a>
                            <button class="delete_btn" onclick="location.href='delete_document.php?id=<?php echo $document['id_document']; ?>'">Supprimer</button>
                            <button onclick="location.href='edit_document.php?id=<?php echo $document['id_document']; ?>'">Modifier</button>
                        </td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="document_id" value="<?php echo $document['id_document']; ?>">
                                <input type="hidden" name="document_status" value="0">
                                <input type="checkbox" id="document_status_<?php echo $document['id_document']; ?>" name="document_status" value="1" <?php echo $document['status'] ? 'checked' : ''; ?>>
                                <label for="document_status_<?php echo $document['id_document']; ?>"></label>
                                <button type="submit">Ok</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-bottom.php';
?>