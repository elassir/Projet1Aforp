<?php
include_once '../controlleur/connexion.php';
include_once '../controlleur/password_utils.php';

/**
 * Crée un nouveau compte apprenti avec mot de passe haché
 * 
 * @param array $data Données de l'apprenti
 * @return int ID de l'apprenti créé ou 0 si erreur
 */
function createApprenti($data) {
    global $pdo;
    
    try {
        // Hasher le mot de passe
        $hashedPassword = hashPassword($data['mot_de_passe']);
        
        $stmt = $pdo->prepare("INSERT INTO apprenti (Nom, Prenom, Mail, Mot_de_passe, classe) 
                               VALUES (?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['mail'],
            $hashedPassword,
            $data['classe']
        ]);
        
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Erreur lors de la création de l'apprenti: " . $e->getMessage());
        return 0;
    }
}

/**
 * Crée un nouveau compte formateur avec mot de passe haché
 * 
 * @param array $data Données du formateur
 * @return int ID du formateur créé ou 0 si erreur
 */
function createFormateur($data) {
    global $pdo;
    
    try {
        // Hasher le mot de passe
        $hashedPassword = hashPassword($data['mot_de_passe']);
        
        $stmt = $pdo->prepare("INSERT INTO formateur (Nom, Prenom, Mail, Mot_de_passe) 
                               VALUES (?, ?, ?, ?)");
        
        $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['mail'],
            $hashedPassword
        ]);
        
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Erreur lors de la création du formateur: " . $e->getMessage());
        return 0;
    }
}

/**
 * Met à jour le mot de passe d'un apprenti avec hachage
 * 
 * @param int $id ID de l'apprenti
 * @param string $newPassword Nouveau mot de passe (en clair)
 * @return bool Succès ou échec
 */
function updateApprentiPassword($id, $newPassword) {
    global $pdo;
    
    try {
        $hashedPassword = hashPassword($newPassword);
        
        $stmt = $pdo->prepare("UPDATE apprenti SET Mot_de_passe = ? WHERE id_apprenti = ?");
        $stmt->execute([$hashedPassword, $id]);
        
        return true;
    } catch (PDOException $e) {
        error_log("Erreur lors de la mise à jour du mot de passe: " . $e->getMessage());
        return false;
    }
}

/**
 * Met à jour le mot de passe d'un formateur avec hachage
 * 
 * @param int $id ID du formateur
 * @param string $newPassword Nouveau mot de passe (en clair)
 * @return bool Succès ou échec
 */
function updateFormateurPassword($id, $newPassword) {
    global $pdo;
    
    try {
        $hashedPassword = hashPassword($newPassword);
        
        $stmt = $pdo->prepare("UPDATE formateur SET Mot_de_passe = ? WHERE id_formateur = ?");
        $stmt->execute([$hashedPassword, $id]);
        
        return true;
    } catch (PDOException $e) {
        error_log("Erreur lors de la mise à jour du mot de passe: " . $e->getMessage());
        return false;
    }
}
?>
