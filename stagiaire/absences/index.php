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
    header("Location: ./index.php");
    die();
}

// Requête SQL pour récupérer les absences de l'utilisateur sélectionné
$sql = "SELECT * FROM absences WHERE id_utilisateur = :user_id ORDER BY date_debut_absence DESC";
$query = $dbh->prepare($sql);
$query->execute(['user_id' => $user_id]);
$absences = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="/assets/css/style_stagiaire/style_stagiaire_absences/style_stagiaire_absences_index.css">

<div>
    <h1>Absences de <?php echo $user['prenom_utilisateur'] . " " . $user['nom_utilisateur']; ?></h1>
    <br>
    <div class="btn-group">
        <button onclick="location.href='../index.php?id=<?php echo $user_id; ?>'">Retour à la page de l'utilisateur</button>
        <button onclick="location.href='new_absence.php?id=<?php echo $user_id; ?>'">Ajouter une absence +</button>
    </div>
    <br>
    <br>

    <?php if (empty($absences)) : ?>
        <p>Aucune absence enregistrée pour cet utilisateur.</p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>Date de début d'absence</th>
                    <th>Date de fin d'absence</th>
                    <th>Motif</th>
                    <th>Document</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($absences as $absence) : ?>
                    <tr>
                        <td><?php echo $absence['date_debut_absence']; ?></td>
                        <td><?php echo $absence['date_fin_absence']; ?></td>
                        <td><?php echo $absence['motif']; ?></td>
                        <td><a href="<?php echo '/upload/' . $document['nom_document']; ?>" download><?php echo $absence['document']; ?></a></td>
                        <td>
                            <button class="modify_btn" onclick="location.href='edit_absence.php?id=<?php echo $absence['id_absence']; ?>'">Modifier</button>
                            <button class="delete_btn" onclick="location.href='delete_absence.php?id=<?php echo $absence['id_absence']; ?>'">Supprimer</button>
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