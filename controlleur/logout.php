<?php
/**
 * Fichier: logout.php
 * 
 * Ce fichier gère la déconnexion des utilisateurs (apprentis et formateurs)
 * Il efface toutes les données de session et redirige vers la page d'accueil
 */

// Démarre la session pour accéder aux variables de session existantes
// Sans cette ligne, on ne pourrait pas manipuler la session actuelle de l'utilisateur
session_start();

// Détruit complètement la session en cours
// Cette fonction efface toutes les données stockées dans la session comme :
// - L'identité de l'utilisateur connecté (ID, nom, prénom)
// - Son rôle (apprenti ou formateur)
// - Toutes ses préférences ou autres informations temporaires
session_destroy();

// Redirige l'utilisateur vers la page d'accueil après déconnexion
// La fonction header envoie une instruction au navigateur pour qu'il se rende à une autre page
// Ici, l'utilisateur sera automatiquement redirigé vers la page d'accueil
header('Location: ../vue/index.php');
?>