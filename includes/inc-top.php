<?php

require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
?>
<style>
    #disconnect {
        width: 40px;
        margin-right: 10px;
    }
</style>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>Document</title>
</head>

<body>
    <header>
        <nav>
            <img src="/assets/images/logo_admin_mns.png" alt="logo_mns" id="logo_mns">
            <div id="bonjour_user">
                <img src="/assets/images/user.png" alt="user" id="user_logo">
                <span>Bonjour</span>
            </div>
            <a href="../../../../logout.php"><img src="/assets/images/turn-off.png" alt="disconnect" id="disconnect"></a>
        </nav>
    </header>
    <main>