<?php
session_start();
include_once '../controlleur/connexion.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Accueil Dynamique</title>
    <link rel="stylesheet" href="./style_index.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body>
    <div class="hero-section">
        <div class="hero-overlay"></div>
        <!-- Sidebar de sélection -->
        <div class="sidebar animate__animated animate__fadeInLeft">
            <div class="auth-cards">
                <a href="../controlleur/login_formateur.php" class="auth-card animate__animated animate__zoomIn">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <h3>Formateur</h3>
                    <p>Espace dédié aux formateurs</p>
                </a>
                <a href="../controlleur/login_apprenti.php" class="auth-card animate__animated animate__zoomIn">
                    <i class="fa-solid fa-user-graduate"></i>
                    <h3>Apprenti</h3>
                    <p>Espace dédié aux apprentis</p>
                </a>
            </div>
        </div>
        <!-- Contenu principal -->
        <div class="container animate__animated animate__fadeInDown">
            <img src="../uploads/logo_aforp.png" alt="Logo Entreprise" class="logo">
            <h1 class="animate__animated animate__bounceInLeft">Bienvenue sur la plateforme </h1>
            <p class="animate__animated animate__bounceInRight">Connectez-vous pour accéder à vos outils et ressources.</p>
        </div>
    </div>
</body>
</html>