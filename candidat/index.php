<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-candidat-check.php';

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

<style>
    .container {
        display: flex;
        flex-direction: row;
        background-color: rgb(16, 21, 47);
        margin: 20px;
        border: 10px solid rgb(47, 60, 110);
    }

    .align {
        display: flex;
        flex-direction: row;
        justify-content: center;
        width: 100%;
        background-color: rgb(47, 60, 110);
        margin-top: 10px;
        padding-top: 2%;
        padding-bottom: 2%;
    }

    .align button {
        width: 150px;
        height: 50px;
        text-transform: uppercase;
        font-size: large;
        font-weight: 700;
        margin-right: 20px;
    }

    .row {
        display: flex;
        flex-direction: column;
        background-color: rgb(16, 21, 47);
        margin-top: 10px;
        margin-bottom: 10px;
        margin-left: 10px;
        margin-right: 10px;
        width: 100%;

    }

    .infos_stagiaires {
        font-weight: bold;
        padding: 30px;
        background-color: rgb(47, 60, 110);
    }

    td {
        border: 0px solid black;
    }

    .intitule_affichage {
        width: 25%;
        text-transform: uppercase;
    }

    .affichage_données {
        background-color: white;
        display: flex;
        justify-content: flex-start;
        color: black;
        margin-left: 100px;
        margin-top: 10px;
    }

    .button_edit_delete {
        display: flex;
        flex-direction: row;
        justify-content: center;
        background-color: rgb(47, 60, 110);
        height: 100%;
        align-items: center;
        margin-top: 10px;
        padding-top: 2%;
        padding-bottom: 2%;
    }

    .button_edit_delete button {
        background-color: white;
        width: 150px;
        height: 50px;
        text-transform: uppercase;
        font-size: large;
        font-weight: 700;
        margin-right: 20px;
    }

    .up_page {
        margin-left: 20px;
    }

    h1 {
        text-align: center;
    }

    .modify_btn:hover {
        background-color: green;
        color: white;
    }

    .delete_btn:hover {
        background-color: red;
        color: black;
    }
</style>
<?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') : ?>
    <button onclick="window.history.back()">Retour</button>
<?php endif; ?>

<div class="up_page">
    <h1>Dossier de l'utilisateur <?php echo $user['prenom_utilisateur'] . " " . $user['nom_utilisateur']; ?></h1>
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
            </tbody>
        </table>
        <div class="align">
            <button onclick="location.href='dossier/index.php?id=<?php echo $user['id_utilisateur']; ?>'">dossier</button>
        </div>
        <div class="button_edit_delete">
            <button class="modify_btn" onclick="location.href='edit_user.php?id=<?php echo $user['id_utilisateur']; ?>'">Modifier</button>
        </div>
    </div>
</div>