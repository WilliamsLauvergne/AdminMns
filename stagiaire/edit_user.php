<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-stagiaire-check.php';

// Vérifier si l'ID de l'utilisateur est présent dans l'URL
if (!isset($_GET['id'])) {
    // Rediriger vers la page de la liste des utilisateurs si l'ID n'est pas présent
    header("Location: index.php");
    die();
}

// Récupérer l'ID de l'utilisateur à partir de l'URL
$id = intval($_GET['id']);

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

// Si le formulaire a été soumis, mettre à jour les informations de l'utilisateur dans la base de données
if (isset($_POST['enregistrer'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $telephone = $_POST['telephone'];
    $role = $_POST['role'];

    // Requête SQL pour mettre à jour les informations de l'utilisateur dans la base de données
    $sql = "UPDATE utilisateur SET nom_utilisateur = :nom, prenom_utilisateur = :prenom, email = :email, password = :password, telephone = :telephone, role = :role WHERE id_utilisateur = :id";
    $query = $dbh->prepare($sql);
    $query->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'password' => $password, 'telephone' => $telephone, 'id' => $id, 'role' => $role]);

    // Rediriger vers la page de visualisation de l'utilisateur mis à jour
    header("Location: /stagiaire/index.php?id=$id");
    die();
}
?>

<link rel="stylesheet" href="/assets/css/style_stagiaire/style_stagiaire_edit.css">

<div class="up_page">
    <h1>Modifier l'utilisateur "<?php echo $user['prenom_utilisateur'] . " " . $user['nom_utilisateur']; ?>"</h1>
    <button class="return" onclick="history.back()">Retour au dossier de l'utilisateur</button>
</div>
<div class="container">
    <form method="post">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" value="<?php echo $user['nom_utilisateur']; ?>"><br>
        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" value="<?php echo $user['prenom_utilisateur']; ?>"><br>
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>"><br>
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" value="<?php echo $user['password']; ?>"><br>
        <label for="telephone">Telephone :</label>
        <input type="number" name="telephone" id="telephone" value="<?php echo $user['telephone']; ?>"><br>
        <br>
        <input class="save" type="submit" name="enregistrer" value="Enregistrer">
    </form>
</div>