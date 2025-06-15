<?php
// Ce fichier gère l'enregistrement des documents pédagogiques soumis via un formulaire

// Inclusion des fichiers nécessaires:
include_once '../controlleur/connexion.php';    // Connexion à la base de données
include_once '../model/DocumentPedago.php';     // Classe qui représente un document pédagogique
include_once '../model/DocumentPedagoRepository.php';  // Classe qui gère l'accès aux documents pédagogiques en base de données

// Vérifie si la requête est de type POST (soumission de formulaire)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données envoyées par le formulaire
    $id_matiere = $_POST['id_matiere'];         // Identifiant de la matière concernée
    $Systeme_concerne = $_POST['Systeme_concerne']; // Système auquel le document est associé
    $Date_Document = $_POST['Date_Document'];   // Date de création du document
    $Type_document = $_POST['Type_document'];   // Type de document (TP, cours, etc.)
    $Doc_file = $_FILES['Doc_file'];            // Fichier téléchargé par l'utilisateur
    $doc_file_path = null;                      // Chemin du fichier (sera défini si le téléchargement réussit)

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

    // Création d'un nouvel objet DocumentPedago avec les données du formulaire
    $documentPedago = new DocumentPedago(
        null, // Nom_matiere n'est plus utilisé dans cette version du code
        $Systeme_concerne,        // Système associé au document
        $Date_Document,           // Date du document
        $Type_document,           // Type de document
        $doc_file_path,           // Chemin du fichier
        null,                     // id_pedagogique (sera généré automatiquement par la base de données)
        $id_matiere               // ID de la matière
    );

    // Sauvegarde du document dans la base de données
    $documentPedagoRepository = new DocumentPedagoRepository($pdo); // Crée un gestionnaire de documents
    $documentPedagoRepository->save($documentPedago);               // Sauvegarde le document

    // Affichage d'un message de confirmation
    echo "<p>Le document pédagogique a été ajouté avec succès. Vous allez être redirigé dans quelques secondes...</p>";
    
    // Script JavaScript pour rediriger l'utilisateur après 3 secondes
    echo "<script>
            setTimeout(function() {
                window.location.href = '../vue/gestion_docPedago.php';
            }, 3000);
          </script>";
}
?>