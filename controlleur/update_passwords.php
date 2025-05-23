<?php
/**
 * Script pour mettre à jour tous les mots de passe existants en version hachée
 * À exécuter une seule fois pour migrer les mots de passe en clair vers des versions hachées
 */

include_once '../controlleur/connexion.php';
include_once '../controlleur/password_utils.php';

// Vérifier si on est admin ou si le script est exécuté en ligne de commande
if (php_sapi_name() !== 'cli' && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')) {
    die("Ce script ne peut être exécuté que par un administrateur ou en ligne de commande.");
}

// Sécurité supplémentaire : demander une confirmation
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    try {
        // Mise à jour des mots de passe des apprentis
        $apprentiCount = 0;
        $stmt = $pdo->query("SELECT id_apprenti, Mot_de_passe FROM apprenti");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Vérifier si le mot de passe est déjà haché
            if (password_get_info($row['Mot_de_passe'])['algo'] === 0) {
                $hashedPassword = hashPassword($row['Mot_de_passe']);
                $updateStmt = $pdo->prepare("UPDATE apprenti SET Mot_de_passe = ? WHERE id_apprenti = ?");
                $updateStmt->execute([$hashedPassword, $row['id_apprenti']]);
                $apprentiCount++;
            }
        }
        
        // Mise à jour des mots de passe des formateurs
        $formateurCount = 0;
        $stmt = $pdo->query("SELECT id_formateur, Mot_de_passe FROM formateur");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Vérifier si le mot de passe est déjà haché
            if (password_get_info($row['Mot_de_passe'])['algo'] === 0) {
                $hashedPassword = hashPassword($row['Mot_de_passe']);
                $updateStmt = $pdo->prepare("UPDATE formateur SET Mot_de_passe = ? WHERE id_formateur = ?");
                $updateStmt->execute([$hashedPassword, $row['id_formateur']]);
                $formateurCount++;
            }
        }
        
        echo "<h1>Mise à jour des mots de passe terminée</h1>";
        echo "<p>Nombre de mots de passe d'apprentis mis à jour : $apprentiCount</p>";
        echo "<p>Nombre de mots de passe de formateurs mis à jour : $formateurCount</p>";
        
    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour des mots de passe : " . $e->getMessage());
    }
} else {
    echo "<h1>Mise à jour des mots de passe</h1>";
    echo "<p>Ce script va mettre à jour tous les mots de passe actuellement en clair dans la base de données vers des versions hachées sécurisées.</p>";
    echo "<p>Cette opération est irréversible. Assurez-vous d'avoir une sauvegarde de la base de données avant de continuer.</p>";
    echo "<p><a href='?confirm=yes' style='background: #e74c3c; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Confirmer la mise à jour</a></p>";
}
?>
