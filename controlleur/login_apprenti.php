<?php
/**
 * Fichier: login_apprenti.php
 * 
 * Ce fichier gère la connexion des apprentis à la plateforme
 * Il vérifie les identifiants et crée une session pour l'utilisateur authentifié
 */

// Configuration pour l'affichage des erreurs (utile pendant le développement)
// En production, ces lignes devraient être supprimées ou commentées
ini_set('display_errors', 1);            // Affiche les erreurs à l'écran
ini_set('display_startup_errors', 1);    // Affiche les erreurs de démarrage de PHP
error_reporting(E_ALL);                  // Rapporte toutes les erreurs PHP

// Démarre une nouvelle session ou reprend une session existante
// Nécessaire pour stocker les informations de l'utilisateur connecté
session_start();

// Inclusion des fichiers nécessaires
include_once '../controlleur/connexion.php';      // Établit la connexion à la base de données
include_once '../controlleur/password_utils.php'; // Fonctions de gestion des mots de passe
include_once '../controlleur/user_management.php'; // Fonctions de gestion des utilisateurs

// Traitement du formulaire de connexion lorsqu'il est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données du formulaire
    $mail = $_POST['mail'];                // Adresse e-mail saisie
    $mot_de_passe = $_POST['mot_de_passe']; // Mot de passe saisi
    
    // Recherche de l'apprenti dans la base de données par son adresse e-mail
    $stmt = $pdo->prepare("SELECT * FROM apprenti WHERE Mail = ?"); // Prépare une requête SQL sécurisée
    $stmt->execute([$mail]);                                        // Exécute la requête avec l'e-mail
    $apprenti = $stmt->fetch(PDO::FETCH_ASSOC);                     // Récupère les données de l'apprenti

    // Si un apprenti avec cette adresse e-mail existe dans la base de données
    if ($apprenti) {
        $authenticated = false;  // Par défaut, l'apprenti n'est pas authentifié
        
        // Système de transition: vérification du format du mot de passe (haché ou texte brut)
        // password_get_info renvoie des informations sur un hash, dont l'algorithme utilisé
        // Si algo = 0, ce n'est pas un hash valide (probablement un texte brut)
        if (password_get_info($apprenti['Mot_de_passe'])['algo'] !== 0) {
            // Le mot de passe est déjà haché, on utilise la vérification sécurisée
            $authenticated = verifyPassword($mot_de_passe, $apprenti['Mot_de_passe']);
        } else {
            // Le mot de passe est encore en texte clair (ancienne méthode non sécurisée)
            if ($mot_de_passe === $apprenti['Mot_de_passe']) {
                $authenticated = true;  // L'authentification réussit
                
                // Mise à jour du mot de passe en version hachée pour plus de sécurité
                // Cela permettra une connexion sécurisée lors des prochaines tentatives
                updateApprentiPassword($apprenti['id_apprenti'], $mot_de_passe);
            }
        }
        
        // Si l'authentification a réussi (mot de passe correct)
        if ($authenticated) {
            // Création des variables de session pour conserver les informations de l'utilisateur connecté
            $_SESSION['user'] = $apprenti;      // Stocke toutes les informations de l'apprenti
            $_SESSION['role'] = 'apprenti';     // Définit son rôle comme "apprenti"

            // Redirection vers la page d'accueil des systèmes
            // La fonction header envoie une instruction au navigateur pour qu'il se rende à une autre page
            header('Location: ../vue/gestion_systemes.php');
            exit;  // Arrête l'exécution du script pour s'assurer que la redirection fonctionne
        }
    }
    
    // Si l'authentification a échoué (e-mail non trouvé ou mot de passe incorrect)
    $error = "Identifiants incorrects";  // Message d'erreur qui sera affiché à l'utilisateur
    // Note: pour des raisons de sécurité, on ne précise pas si c'est l'email ou le mot de passe qui est incorrect
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">                                <!-- Définit l'encodage du document -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Optimisation pour appareils mobiles -->
    <title>Connexion Apprenti</title>                     <!-- Titre de la page qui s'affiche dans l'onglet du navigateur -->
    <link rel="stylesheet" href="../vue/style_index.css"> <!-- Lien vers la feuille de style CSS -->
</head>
<body>
    <!-- Container principal qui organise la page en deux sections -->
    <div class="login-container">
        <!-- Section de gauche avec le logo -->
        <div class="image-section">
            <img src="../uploads/logo_aforp.png" alt="Logo Entreprise" class="login-logo"> <!-- Affiche le logo -->
        </div>
        
        <!-- Section de droite avec le formulaire -->
        <div class="form-section">
            <h1>Connexion Apprenti</h1>  <!-- Titre du formulaire -->
            
            <!-- Affiche un message d'erreur si l'authentification a échoué -->
            <?php if (isset($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>  <!-- Affichage sécurisé avec htmlspecialchars -->
            <?php endif; ?>
            
            <!-- Formulaire de connexion - Enverra les données à cette même page en POST -->
            <form action="login_apprenti.php" method="POST">
                <!-- Groupe pour l'adresse e-mail -->
                <div class="input-group">
                    <label for="mail">Email :</label>  <!-- Étiquette du champ -->
                    <input type="email" id="mail" name="mail" required>  <!-- Champ de saisie de l'email (obligatoire) -->
                </div>
                
                <!-- Groupe pour le mot de passe -->
                <div class="input-group">
                    <label for="mot_de_passe">Mot de passe :</label>  <!-- Étiquette du champ -->
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>  <!-- Champ de saisie masqué (obligatoire) -->
                </div>
                
                <!-- Bouton pour soumettre le formulaire -->
                <button type="submit" class="btn">Se connecter</button>
            </form>
            
            <!-- Lien vers la page d'inscription pour les nouveaux utilisateurs -->
            <p>Pas encore inscrit ? <a href="../controlleur/register_apprenti.php">Créer un compte</a></p>
        </div>
    </div>
</body>
</html>