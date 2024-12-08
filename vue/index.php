<?php
session_start();
include_once '../controlleur/connexion.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Accueil</title>
    <link rel="stylesheet" href="./style_index.css">
</head>
<body>
    <h1>Bienvenue sur la plateforme de gestion</h1>
    <p>Veuillez vous connecter en tant que :</p>
    <div class="auth-buttons">
        <a href="../controlleur/login_formateur.php" class="auth-button">Formateur</a>
        <a href="../controlleur/login_apprenti.php" class="auth-button">Apprenti</a>
    </div>
</body>
</html>