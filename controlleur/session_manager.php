<?php
/**
 * Fichier: session_manager.php
 * 
 * Ce fichier gère la durée de vie des sessions utilisateur
 * Il vérifie l'activité et déconnecte les utilisateurs inactifs depuis trop longtemps
 */

// Durée maximale d'inactivité en secondes (30 minutes)
define('SESSION_TIMEOUT', 1800);

/**
 * Vérifie si la session utilisateur est toujours valide ou si elle a expiré
 * 
 * @return bool Retourne true si la session est valide, false sinon
 */
function isSessionValid() {
    // Vérifier d'abord si l'utilisateur est connecté
    if (!isset($_SESSION['user']) || !isset($_SESSION['last_activity'])) {
        return false;
    }
    
    // Vérifier le délai d'inactivité
    $current_time = time();
    $last_activity = $_SESSION['last_activity'];
    
    if (($current_time - $last_activity) > SESSION_TIMEOUT) {
        // La session a expiré, on déconnecte l'utilisateur
        session_unset();     // Supprime toutes les variables de session
        session_destroy();   // Détruit complètement la session
        return false;
    }
    
    // Mise à jour du timestamp d'activité
    $_SESSION['last_activity'] = $current_time;
    return true;
}
?>
