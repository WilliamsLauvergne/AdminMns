<?php

require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-session-check.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-admin-check.php';


// Si le formulaire a été soumis, ajouter l'utilisateur à la base de données
if (isset($_POST['submit'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $telephone = htmlspecialchars($_POST['telephone']);
    $role = $_POST['role'];

    // Hash du mot de passe
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Requête SQL pour ajouter un nouvel utilisateur dans la base de données
    $sql = "INSERT INTO utilisateur (nom_utilisateur, prenom_utilisateur, email, password, telephone, role) VALUES (:nom, :prenom, :email, :password, :telephone, :role)";
    $query = $dbh->prepare($sql);
    $query->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'password' => $passwordHash, 'telephone' => $telephone, 'role' => $role]);

    // Rediriger vers la page de la liste des utilisateurs
    header("Location: index.php");
    die();
}
?>

<link rel="stylesheet" href="/assets/css/style_admin/style_new-user.css">

<h1>Ajouter un nouvel utilisateur</h1>
<button onclick="location.href='index.php'">Retour à la liste des utilisateurs</button>
<div class="container">
    <form method="post">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom"><br>
        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom"><br>
        <label for="email">Email :</label>
        <input type="email" name="email" id="email"><br>
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password"><br>
        <label for="telephone">Telephone :</label>
        <input type="text" name="telephone" id="telephone"><br>
        <label for="role">Rôle :</label>
        <select name="role" id="role">
            <option value="administratif">Administratif</option>
            <option value="stagiaire">Stagiaire</option>
            <option value="candidat">Candidat</option>
        </select><br>
        <input class="submit" type="submit" name="submit" value="submit">
    </form>
</div>