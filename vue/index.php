<?php
// Démarre la session pour permettre de stocker des informations spécifiques à l'utilisateur
session_start();
// Inclut le fichier de connexion à la base de données
include_once '../controlleur/connexion.php';

// Vérifie si on vient d'une session expirée
$session_expired = isset($_GET['session_expired']) ? true : false;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Accueil Dynamique</title>
    <!-- Lien vers la feuille de style CSS personnalisée -->
    <link rel="stylesheet" href="./style_index.css">
    <!-- Intégration de Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Intégration de la bibliothèque Animate.css pour les animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        .session-expired {
            background-color: #ffdddd;
            color: #dd3333;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            width: 100%;
            animation: fadeOut 0.5s 5s forwards;
        }
        @keyframes fadeOut {
            to {
                opacity: 0;
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Section principale "hero" avec fond -->
    <div class="hero-section">
        <!-- Couche semi-transparente par-dessus le fond -->
        <div class="hero-overlay"></div>
        
        <!-- Barre latérale avec les options de connexion -->
        <div class="sidebar animate__animated animate__fadeInLeft">
            <?php if ($session_expired): ?>
                <div class="session-expired animate__animated animate__fadeIn">
                    <i class="fas fa-exclamation-triangle"></i>
                    Votre session a expiré pour des raisons de sécurité. Veuillez vous reconnecter.
                </div>
            <?php endif; ?>
            
            <div class="auth-cards">
                <!-- Carte cliquable pour accéder à l'espace formateur -->
                <a href="../controlleur/login_formateur.php" class="auth-card animate__animated animate__zoomIn">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <h3>Formateur</h3>
                    <p>Espace dédié aux formateurs</p>
                </a>
                
                <!-- Carte cliquable pour accéder à l'espace apprenti -->
                <a href="../controlleur/login_apprenti.php" class="auth-card animate__animated animate__zoomIn">
                    <i class="fa-solid fa-user-graduate"></i>
                    <h3>Apprenti</h3>
                    <p>Espace dédié aux apprentis</p>
                </a>
            </div>
        </div>
        
        <!-- Contenu principal de la page d'accueil -->
        <div class="container animate__animated animate__fadeInDown">
            <!-- Logo de l'entreprise -->
            <img src="../uploads/logo_aforp.png" alt="Logo Entreprise" class="logo">
            <!-- Titre principal avec animation -->
            <h1 class="animate__animated animate__bounceInLeft">Bienvenue sur la plateforme </h1>
            <!-- Texte explicatif avec animation -->
            <p class="animate__animated animate__bounceInRight">Connectez-vous pour accéder à vos outils et ressources.</p>
        </div>
    </div>
</body>
</html>