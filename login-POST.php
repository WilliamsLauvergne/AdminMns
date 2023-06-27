<?php
session_start();
if (isset($_POST['email']) && isset($_POST['password'])) {
    // Database connection
    $db_username = 'root';
    $db_password = 'W8di_7dpM/Bs964';
    $db_name = 'adminmns';
    $db_host = '127.0.0.1';

    try {
        $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }

    // Sanitize user input
    $email = $db->quote(htmlspecialchars($_POST['email']));
    $password = htmlspecialchars($_POST['password']);

    if ($email !== "" && $password !== "") {
        $requete = "SELECT role, id_utilisateur, password FROM utilisateur WHERE email = $email";
        $exec_requete = $db->query($requete);
        $reponse = $exec_requete->fetch(PDO::FETCH_ASSOC);
        $role = $reponse['role'];
        $id = $reponse['id_utilisateur'];
        $hashedPassword = $reponse['password'];

        // Verify the password using password_verify
        if (password_verify($password, $hashedPassword)) {
            // Password is correct

            $user = array(
                'email' => $_POST['email'],
                'role' => $role,
                'id_utilisateur' => $id
            );

            if ($role == "administratif") {
                $_SESSION['user'] = $user;
                header('Location: /admin/index.php');
            } elseif ($role == "stagiaire") {
                $_SESSION['user'] = $user;
                header("Location: /stagiaire/index.php?id=$id");
            } elseif ($role == "candidat") {
                $_SESSION['user'] = $user;
                header("Location: /candidat/index.php?id=$id");
            } else {
                header('Location: index.php?erreur=1'); // Incorrect username or password
            }
        } else {
            header('Location: index.php?erreur=1'); // Incorrect username or password
        }
    } else {
        header('Location: index.php?erreur=2'); // Empty username or password
    }
} else {
    header('Location: admin/index.php');
}
$db = null; // Close the database connection
