<?php 
/**
 * Fichier: enregistrerSysteme.php
 * 
 * Ce fichier gère l'enregistrement des systèmes techniques dans la base de données
 * Il traite les informations soumises via le formulaire d'ajout de système et
 * gère également le téléchargement des images associées aux systèmes
 */

// Inclusion des fichiers nécessaires:
include_once '../controlleur/connexion.php';    // Connexion à la base de données (fournit l'objet $pdo)
include_once '../model/systeme.php';            // Définition de la classe qui représente un système technique
include_once '../model/systemeRepository.php';  // Classe qui gère les opérations CRUD pour les systèmes en base de données

// Vérifie si la requête est de type POST (soumission de formulaire)
// Cette condition permet de n'exécuter le code que lorsque le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Récupération des données envoyées par le formulaire
    // On utilise $_POST qui contient toutes les données envoyées par le formulaire
    $Nom = $_POST['Nom'];                                   // Récupère le nom du système
    $date_derniere_mise_a_jour = $_POST['date_mise_a_jour']; // Récupère la date de dernière mise à jour
    $Numero_de_serie = $_POST['Numero_de_serie'];           // Récupère le numéro de série du système
    $Fabriquant = $_POST['Fabriquant'];                     // Récupère le SIRET du fabricant
    $Date_fabrication = $_POST['Date_fabrication'];         // Récupère la date de fabrication du système
    $Description = $_POST['Description'];                   // Récupère la description détaillée du système    // Traitement de l'image du système
    // En PHP, les fichiers téléchargés sont stockés dans la superglobale $_FILES
    $image_systeme = $_FILES['image_systeme'];              // Récupère les informations sur le fichier image téléchargé
    $image_path = null;                                     // Par défaut, pas de chemin d'image
    
    // Vérifie si une image a été téléchargée et s'il n'y a pas eu d'erreur lors du téléchargement
    // error = 0 signifie qu'il n'y a pas eu d'erreur pendant le téléchargement
    if ($image_systeme && $image_systeme['error'] == 0) {
        echo "<h2>TEST Ajout d'un nouveau système</h2>";    // Message de test (à supprimer en production)
        
        $target_dir = "../uploads/";                        // Définit le dossier où seront stockées les images
        $target_file = $target_dir . basename($image_systeme['name']); // Construit le chemin complet du fichier
        
        // Déplace l'image depuis le dossier temporaire vers notre dossier d'uploads
        // tmp_name est le chemin temporaire où PHP a stocké le fichier après téléchargement
        if (move_uploaded_file($image_systeme['tmp_name'], $target_file)) {
            // Si le déplacement a réussi, on garde seulement le nom du fichier (pas le chemin complet)
            $image_path = basename($image_systeme['name']);
            // Ce nom sera stocké en base de données pour pouvoir retrouver l'image plus tard
        }
        // Si le téléchargement échoue, $image_path reste null et aucune image ne sera associée au système
    }
      // Création d'un nouvel objet Systeme avec les données du formulaire
    // On instancie (crée) un nouvel objet de la classe Systeme avec toutes les informations récupérées
    $systeme = new Systeme(
        $Nom,                       // Le nom du système technique
        $date_derniere_mise_a_jour, // La date de dernière mise à jour du système
        $image_path,                // Le chemin vers l'image du système (peut être null)
        $Numero_de_serie,           // Le numéro de série unique du système
        $Fabriquant,                // Le SIRET du fabricant qui a produit ce système
        $Date_fabrication,          // La date de fabrication du système
        $Description                // La description détaillée du système et de ses fonctionnalités
    );
    
    // Sauvegarde du système dans la base de données
    // Étape 1: On crée un objet SystemeRepository qui nous permet de manipuler les systèmes en BDD
    $systemeRepository = new SystemeRepository($pdo);  // On lui passe la connexion à la base de données
    
    // Étape 2: On utilise la méthode save pour enregistrer notre nouveau système en base de données
    $systemeRepository->save($systeme);                // Cette méthode va exécuter les requêtes SQL nécessaires
    
    // Affichage d'un message de confirmation à l'utilisateur
    // Ce message s'affichera après l'enregistrement réussi du système
    echo "<p>Le système a été ajouté avec succès. Vous allez être redirigé dans quelques secondes...</p>";
    
    // Note: Il serait préférable d'ajouter ici une redirection vers la page de gestion des systèmes
    // après un court délai (avec JavaScript) ou immédiatement (avec header)
}

// Fin du fichier enregistrerSysteme.php
?>