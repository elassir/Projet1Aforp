<?php
// Ce fichier gère l'enregistrement des documents techniques soumis via un formulaire

// Inclusion des fichiers nécessaires:
include_once '../controlleur/connexion.php';        // Connexion à la base de données
include_once '../model/DocumentTechnique.php';      // Classe qui représente un document technique
include_once '../model/DocumentTechniqueRepository.php';  // Classe qui gère l'accès aux documents techniques en base de données

// Vérifie si la requête est de type POST (soumission de formulaire)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données envoyées par le formulaire
    $Nom_doc_tech = $_POST['Nom_doc_tech'];     // Nom du document technique
    $Date = $_POST['Date'];                     // Date de création/modification du document
    $Categorie = $_POST['Categorie'];           // Catégorie du document (manuel, schéma, etc.)
    $Systeme_concerne = $_POST['Systeme_concerne']; // Système auquel le document est associé
    $Doc_file = $_FILES['Doc_file'];            // Fichier téléchargé par l'utilisateur
    $Version = $_POST['Version'];               // Version du document

    // Initialisation du chemin du fichier
    $doc_file_path = null;
    
    // Traitement du fichier téléchargé
    if ($Doc_file && $Doc_file['error'] == 0) {  // Vérifie si un fichier a été téléchargé sans erreur
        $target_dir = "../uploads/";             // Répertoire où les fichiers seront stockés
        $target_file = $target_dir . basename($Doc_file['name']); // Chemin complet du fichier
        // Déplace le fichier téléchargé vers le répertoire cible
        if (move_uploaded_file($Doc_file['tmp_name'], $target_file)) {
            $doc_file_path = basename($Doc_file['name']); // Sauvegarde uniquement le nom du fichier
        }
        // Note: Si le téléchargement échoue, doc_file_path reste null
    }

    // Création d'un nouvel objet DocumentTechnique avec les données du formulaire
    $documentTechnique = new DocumentTechnique(
        $Nom_doc_tech,        // Nom du document
        $Date,                // Date 
        $Categorie,           // Catégorie
        $Systeme_concerne,    // Système associé
        $doc_file_path,       // Chemin du fichier
        $Version              // Version du document
    );

    // Sauvegarde du document dans la base de données
    $documentTechniqueRepository = new DocumentTechniqueRepository($pdo); // Crée un gestionnaire de documents
    $documentTechniqueRepository->save($documentTechnique);               // Sauvegarde le document

    // Affichage d'un message de confirmation
    echo "<p>Le document technique a été ajouté avec succès. Vous allez être redirigé dans quelques secondes...</p>";
    
    // Script JavaScript pour rediriger l'utilisateur après 3 secondes
    echo "<script>
            setTimeout(function() {
                window.location.href = '../vue/gestion_doc.php';
            }, 3000);
          </script>";
}
?>
