<?php
/**
 * Utilitaires pour le hachage sécurisé des mots de passe
 */

/**
 * Hache un mot de passe de manière sécurisée
 * 
 * @param string $password Mot de passe en clair
 * @return string Mot de passe haché
 */
function hashPassword($password) {
    // Utilise l'algorithme de hachage Bcrypt (par défaut)
    // Le coût 12 est un bon équilibre entre sécurité et performance
    return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
}

/**
 * Vérifie si un mot de passe correspond au hash stocké
 * 
 * @param string $password Mot de passe en clair à vérifier
 * @param string $hash Hash stocké dans la base de données
 * @return bool True si le mot de passe est correct, false sinon
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Détermine si un hash nécessite d'être recalculé
 * Par exemple lorsque l'algorithme ou les options ont changé
 * 
 * @param string $hash Hash stocké
 * @return bool True si rehash nécessaire
 */
function needsRehash($hash) {
    return password_needs_rehash($hash, PASSWORD_DEFAULT, ['cost' => 12]);
}
?>
