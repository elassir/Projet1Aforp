<?php
/**
 * Fichier: enregistrerDevoirApprenti.php
 * 
 * Ce contrôleur gère l'envoi de devoirs par les apprentis en réponse à une consigne
 * Il associe un document téléchargé au document pédagogique concerné et à l'apprenti
 */

// Inclusion des fichiers nécessaires
include_once '../controlleur/connexion.php';             // Connexion à la base de données
include_once '../model/DocumentPedago.php';              // Classe qui représente un document pédagogique
include_once '../model/DocumentPedagoRepository.php';    // Classe qui gère l'accès aux documents en base de données

// Démarre la session pour accéder aux informations d'utilisateur
session_start();

// Vérification de sécurité : seuls les apprentis peuvent accéder à cette fonctionnalité
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'apprenti') {
    // Redirection si un utilisateur non autorisé essaie d'accéder
    header('Location: ../vue/index.php?access_denied=1');
    exit;
}

// Vérifie si la requête est de type POST (soumission de formulaire)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données envoyées par le formulaire
    $consigne_id = $_POST['consigne_id'];       // ID de la consigne à laquelle ce devoir répond
    $apprenti_id = $_SESSION['user']['id_apprenti']; // ID de l'apprenti (récupéré de la session)
    $Doc_file = $_FILES['Doc_file'];            // Fichier téléchargé par l'apprenti
    $doc_file_path = null;                      // Chemin du fichier (sera défini si le téléchargement réussit)

    // Récupération des informations de la consigne
    $documentPedagoRepository = new DocumentPedagoRepository($pdo);
    $consigne = $documentPedagoRepository->findById($consigne_id);
    
    // Vérification que la consigne existe
    if (!$consigne) {
        echo "<p>Erreur: Consigne introuvable.</p>";
        echo "<p><a href='../vue/gestion_docPedago.php'>Retour à la liste des documents</a></p>";
        exit;
    }

    // Traitement du fichier téléchargé
    if ($Doc_file && $Doc_file['error'] == 0) {  // Vérifie si un fichier a été téléchargé sans erreur
        // Génère un nom de fichier unique pour éviter les écrasements
        $file_extension = pathinfo($Doc_file['name'], PATHINFO_EXTENSION);
        $unique_file_name = "devoir_" . $apprenti_id . "_" . $consigne_id . "_" . time() . "." . $file_extension;
        
        $target_dir = "../uploads/";             // Répertoire où les fichiers seront stockés
        $target_file = $target_dir . $unique_file_name; // Chemin complet du fichier
        
        // Déplace le fichier téléchargé vers le répertoire cible
        if (move_uploaded_file($Doc_file['tmp_name'], $target_file)) {
            $doc_file_path = $unique_file_name; // Sauvegarde uniquement le nom du fichier
        } else {
            echo "<p>Erreur: Impossible de télécharger le fichier.</p>";
            echo "<p><a href='../vue/gestion_docPedago.php'>Retour à la liste des documents</a></p>";
            exit;
        }
    } else {
        echo "<p>Erreur: Veuillez sélectionner un fichier valide.</p>";
        echo "<p><a href='../vue/gestion_docPedago.php'>Retour à la liste des documents</a></p>";
        exit;
    }

    // Création d'un nouvel objet DocumentPedago pour le devoir
    $devoir = new DocumentPedago(
        null, // Nom_matiere n'est plus utilisé dans cette version du code
        $consigne->getSystemeConcerne(),  // Même système que la consigne
        date('Y-m-d'),                    // Date du jour
        'DEVOIR',                         // Type de document
        $doc_file_path,                   // Chemin du fichier
        null,                             // id_pedagogique (sera généré automatiquement)
        $consigne->getIdMatiere()         // Même matière que la consigne
    );    try {
        // Sauvegarde du devoir dans la base de données (cette méthode gère déjà sa propre transaction)
        $documentPedagoRepository->save($devoir);
        $devoir_id = $devoir->getIdPedagogique();
        
        // Début d'une nouvelle transaction séparée pour l'association apprenti-devoir
        try {
            $pdo->beginTransaction();
            
            // Association du devoir à l'apprenti dans la table apprenti_devoir
            $stmt = $pdo->prepare("INSERT INTO apprenti_devoir (Apprenti, Devoir) VALUES (?, ?)");
            $stmt->execute([$apprenti_id, $devoir_id]);
            
            // Validation de la transaction
            $pdo->commit();
        } catch (Exception $innerEx) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $innerEx; // Re-lancer l'exception pour qu'elle soit attrapée par le catch externe
        }
        
        // Affichage d'un message de confirmation
        echo "<p>Votre devoir a été envoyé avec succès. Vous allez être redirigé dans quelques secondes...</p>";
        
        // Script JavaScript pour rediriger l'utilisateur après 3 secondes
        echo "<script>
                setTimeout(function() {
                    window.location.href = '../vue/gestion_docPedago.php';
                }, 3000);
              </script>";
                } catch (Exception $e) {
        // En cas d'erreur, annulation de la transaction si elle est active
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo "<p>Une erreur est survenue: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><a href='../vue/gestion_docPedago.php'>Retour à la liste des documents</a></p>";
    }
} else {
    // Si la page est accédée sans soumission de formulaire, redirection
    header('Location: ../vue/gestion_docPedago.php');
    exit;
}
?>
