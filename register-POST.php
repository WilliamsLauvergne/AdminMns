<?php

session_start();
unset($_SESSION['error']);

if(!empty($_POST['submit']))
{

    $errors = [];

    // On vérifie tous les champs du formulaire

    if(empty($_POST['lastname']))
        $errors['lastname'] = "Votre nom est obligatoire.";

    if(empty($_POST['firstname']))
        $errors['firstname'] = "Votre prénom est obligatoire.";


    if(empty($_POST['email']))
        $errors['email'] = "Votre email est obligatoire.";

    if(empty($_POST['password']))
        $errors['password'] = "Le mot de passe est obligatoire.";

    if(empty($_POST['password_confirm']))
        $errors['password_confirm'] = "Le mot de passe est obligatoire.";
    
    if($_POST['password'] != $_POST['password_confirm'])
        $errors['password_confirm'] = "Les mots de passe ne sont pas identiques.";


    if(count($errors) > 0)
    {
        $_SESSION['errors'] = $errors;
        $_SESSION['values'] = $_POST;
        header("Location: /register.php"); die;
    }

    include 'includes/inc-db-connect.php';

    // On vérifie que l'utilisateur n'existe pas
    $sql = "SELECT * FROM utilisateur WHERE email_utilisateur = :email";
    $query = $dbh->prepare($sql);
    $res = $query->execute([
        'email' => $_POST['email']
    ]);

    if($query->rowCount() > 0)
    {
        $_SESSION['error'] = "Un utilisateur existe déjà avec cette adresse email.";
        header("Location: /register.php"); die;
    }

    // On insère l'utilisateur en BDD
    $sql = "INSERT INTO utilisateur (nom_utilisateur, prenom_utilisateur, email_utilisateur, mdp_utilisateur) 
    VALUES (:nom_utilisateur, :prenom_utilisateur, :email_utilisateur, :mdp_utilisateur)";
    $query = $dbh->prepare($sql);
    $res = $query->execute([
        'nom_utilisateur' => $_POST['lastname'],
        'prenom_utilisateur' => $_POST['firstname'],
        'email_utilisateur' => $_POST['email'],
        'mdp_utilisateur' => password_hash($_POST['password'], PASSWORD_DEFAULT)
    ]);

    if($res)
    {
        header("Location: /"); exit;
    }
    else
    {
        $_SESSION['error'] = "Un erreur est survenue.";
        header("Location: /register.php"); die;
    }
    
}

