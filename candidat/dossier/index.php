<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-candidat-check.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_GET['id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: index.php");
    die();
}

// Récupérer l'ID de l'utilisateur à partir de l'URL
$id = intval($_GET['id']); //la ligne $id = intval($_GET['id']); permet de récupérer l'ID de l'utilisateur et de le convertir en entier. Cela permet de s'assurer que l'ID est bien un nombre entier et évite les injections SQL.

// Requête SQL pour récupérer les documents de l'utilisateur connecté
$sql = "SELECT * FROM document WHERE id_utilisateur = :id_utilisateur";
$query = $dbh->prepare($sql);
$query->bindParam(':id_utilisateur', $id, PDO::PARAM_INT);
$query->execute();
$documents = $query->fetchAll(PDO::FETCH_ASSOC);
?>

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
    <h1>Liste des documents</h1>
    <br>
    <div>
        <button onclick="history.back()">Retour à la page de l'utilisateur</button>
        <button onclick="location.href='../../admin/documents/new_document.php?id=<?php echo $id; ?>'">Ajouter un document +</button>
    </div>
    <br>
    <br>

    <?php if (empty($documents)) : ?>
        <p>Aucun document enregistré pour cet utilisateur.</p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>Nom du document</th>
                    <th>Date de création</th>
                    <th>Télécharger</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $document) : ?>
                    <tr>
                        <td><?php echo $document['nom_document']; ?></td>
                        <td><?php echo $document['date_creation']; ?></td>
                        <td><a href="<?php echo '/upload/' . $document['nom_document']; ?>" download>Télécharger</a></td>
                        <td>
                            <?php if ($document['status']) : ?>
                                <button class="valid_btn">Validé</button>
                            <?php else : ?>
                                <button class="invalid_btn">Non validé</button>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="modify_btn" onclick="location.href='../../admin/documents/edit_document.php?id=<?php echo $document['id_document']; ?>'">Modifier</button>
                            <button class="delete_btn" onclick="location.href='../../admin/documents/delete_document.php?id=<?php echo $document['id_document']; ?>'">Supprimer</button>
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