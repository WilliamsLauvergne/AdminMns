<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-admin-check.php';

// Vérifier si un filtre de rôle est défini
$roleFilter = isset($_GET['role']) ? $_GET['role'] : '';

// Vérifier si une recherche est effectuée
$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';

// Requête SQL de base pour récupérer les utilisateurs
$sql = "SELECT * FROM utilisateur WHERE 1=1";

// Tableau pour stocker les paramètres de la requête préparée
$params = [];

// Ajouter le filtre de rôle à la requête SQL
if ($roleFilter === 'candidat') {
    $sql .= " AND role = 'candidat'";
} elseif ($roleFilter === 'stagiaire') {
    $sql .= " AND role = 'stagiaire'";
} elseif ($roleFilter === 'administratif') {
    $sql .= " AND role = 'administratif'";
}

// Ajouter la recherche à la requête SQL
if (!empty($searchKeyword)) {
    $sql .= " AND (nom_utilisateur LIKE :keyword OR prenom_utilisateur LIKE :keyword OR email LIKE :keyword OR telephone LIKE :keyword)";
    $params[':keyword'] = "%$searchKeyword%";
}

$query = $dbh->prepare($sql);
$query->execute($params);
$users = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="/assets/css/style_admin/style_admin_index.css">
<style>
    .search-filter form {
        margin-left: 37%;
        margin-right: 30%;
    }

    .search-filter input {
        width: 50%;
    }
</style>

<div>
    <center>
        <h1>Liste des utilisateurs</h1>
    </center>
    <p>
        <a class="add_user_btn" href="new_user.php">Ajouter un utilisateur +</a>
    </p>
    <div style="height:500px; overflow:auto" class="search-filter">
        <form action="" method="GET">
            <select name="role">
                <option value="">Tous les rôles</option>
                <option value="candidat" <?php echo $roleFilter === 'candidat' ? 'selected' : ''; ?>>Candidat</option>
                <option value="stagiaire" <?php echo $roleFilter === 'stagiaire' ? 'selected' : ''; ?>>Stagiaire</option>
                <option value="administratif" <?php echo $roleFilter === 'administratif' ? 'selected' : ''; ?>>Administratif</option>
            </select>
            <input type="text" name="search" placeholder="Rechercher par nom, prénom, email ou téléphone" value="<?php echo $searchKeyword; ?>">
            <button type="submit">Filtrer</button>
        </form>
        <table class="tableListeAdmin">
            <tr>
                <th>Role</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td><?php echo $user['role']; ?></td>
                    <td><?php echo $user['nom_utilisateur']; ?></td>
                    <td><?php echo $user['prenom_utilisateur']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['telephone']; ?></td>
                    <td>
                        <a class="view_doss_btn" href="view_user.php?id=<?php echo $user['id_utilisateur']; ?>">voir dossier</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

</html>

<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-bottom.php';
?>