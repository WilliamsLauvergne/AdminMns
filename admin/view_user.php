<?php

require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-admin-check.php';


// Vérifier si l'ID de l'utilisateur est présent dans l'URL
if (!isset($_GET['id'])) {
    // Rediriger vers la page de la liste des utilisateurs si l'ID n'est pas présent
    header("Location: index.php");
    die();
}

// Récupérer l'ID de l'utilisateur à partir de l'URL
$id = intval($_GET['id']); //la ligne $id = intval($_GET['id']); permet de récupérer l'ID de l'utilisateur et de le convertir en entier. Cela permet de s'assurer que l'ID est bien un nombre entier et évite les injections SQL.

// Requête SQL pour récupérer les informations de l'utilisateur sélectionné
$sql = "SELECT * FROM utilisateur WHERE id_utilisateur = :id";
$query = $dbh->prepare($sql);
$query->execute(['id' => $id]);
$user = $query->fetch(PDO::FETCH_ASSOC);


// Vérifier si l'utilisateur existe dans la base de données
if (!$user) {
    // Rediriger vers la page de la liste des utilisateurs si l'utilisateur n'existe pas
    header("Location: index.php");
    die();
}
?>

<link rel="stylesheet" href="/assets/css/style_admin/style_admin_view-user.css">

<div class="up_page">
    <h1>Dossier de l'utilisateur <?php echo $user['prenom_utilisateur'] . " " . $user['nom_utilisateur']; ?></h1>
    <button onclick="location.href='index.php'">Retour à la liste des utilisateurs</button>
</div>
<div class="container">
    <div class="row">
        <table class="infos_stagiaires" cellpadding="15">
            <tbody>
                <tr>
                    <td class="intitule_affichage">Nom : </td>
                    <td class="affichage_données"><?php echo $user['nom_utilisateur']; ?></td>
                </tr>
                <tr>
                    <td class="intitule_affichage">Prenom : </td>
                    <td class="affichage_données"><?php echo $user['prenom_utilisateur']; ?></td>
                </tr>
                <tr>
                    <td class="intitule_affichage">Email : </td>
                    <td class="affichage_données"><?php echo $user['email']; ?></td>
                </tr>
                <tr>
                    <td class="intitule_affichage">Telephone : </td>
                    <td class="affichage_données"><?php echo $user['telephone']; ?></td>
                </tr>
                <tr>
                    <td class="intitule_affichage">Rôle : </td>
                    <td class="affichage_données"><?php echo $user['role']; ?></td>
                </tr>
            </tbody>
        </table>
        <div class="align">
            <?php if ($user['role'] !== "candidat") : ?>
                <button onclick="location.href='absences/index.php?id=<?php echo $user['id_utilisateur']; ?>'">Absences</button>
                <button onclick="location.href='retards/index.php?id=<?php echo $user['id_utilisateur']; ?>'">Retards</button>
            <?php endif; ?>
            <button onclick="location.href='documents/index.php?id=<?php echo $user['id_utilisateur']; ?>'">Documents</button>
        </div>

        <div class="button_edit_delete">
            <button class="modify_btn" onclick="location.href='edit_user.php?id=<?php echo $user['id_utilisateur']; ?>'">Modifier</button>
            <button class="delete_btn" onclick="location.href='delete_user.php?id=<?php echo $user['id_utilisateur']; ?>'">Supprimer</button>
        </div>
    </div>
</div>

<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-bottom.php';
?>