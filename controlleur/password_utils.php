<?php
/**
 * Utilitaires pour le hachage sécurisé des mots de passe
 * 
 * Ce fichier contient des fonctions pour la gestion sécurisée des mots de passe.
 * Le hachage est une technique qui transforme un mot de passe en texte chiffré 
 * impossible à décrypter, ce qui permet de stocker les mots de passe de façon
 * sécurisée dans la base de données.
 */

/**
 * Vérifie si un mot de passe respecte les critères de complexité
 * - Au moins 8 caractères
 * - Au moins 1 lettre majuscule
 * - Au moins 1 chiffre
 * 
 * @param string $password Mot de passe à vérifier
 * @return bool|string True si valide, message d'erreur sinon
 */
function validatePasswordStrength($password) {
    // Vérification de la longueur minimale
    if (strlen($password) < 8) {
        return "Le mot de passe doit contenir au moins 8 caractères";
    }
    
    // Vérification de la présence d'au moins une lettre majuscule
    if (!preg_match('/[A-Z]/', $password)) {
        return "Le mot de passe doit contenir au moins une lettre majuscule";
    }
    
    // Vérification de la présence d'au moins un chiffre
    if (!preg_match('/[0-9]/', $password)) {
        return "Le mot de passe doit contenir au moins un chiffre";
    }
    
    return true;
}

/**
 * Hache un mot de passe de manière sécurisée
 * 
 * Cette fonction prend un mot de passe en texte brut et le transforme en une chaîne 
 * cryptographiquement sécurisée qui peut être stockée en base de données.
 * Il est impossible de retrouver le mot de passe original à partir du hash.
 * 
 * @param string $password Mot de passe en clair (celui entré par l'utilisateur)
 * @return string Mot de passe haché (version cryptée à stocker en base)
 */
function hashPassword($password) {
    // Utilise l'algorithme de hachage Bcrypt (par défaut)
    // Le coût 12 est un bon équilibre entre sécurité et performance
    // Plus le coût est élevé, plus la génération du hash est lente (et plus sécurisée)
    return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
}

/**
 * Vérifie si un mot de passe correspond au hash stocké
 * 
 * Cette fonction permet de vérifier si le mot de passe saisi par l'utilisateur
 * correspond bien au hash stocké dans la base de données, sans avoir besoin
 * de connaître le mot de passe original.
 * 
 * @param string $password Mot de passe en clair à vérifier (saisi par l'utilisateur)
 * @param string $hash Hash stocké dans la base de données
 * @return bool True si le mot de passe est correct, false sinon
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Détermine si un hash nécessite d'être recalculé
 * 
 * Cette fonction vérifie si un hash est encore sécurisé selon les standards actuels.
 * Si les algorithmes ou options de sécurité ont évolué depuis la création du hash,
 * il peut être nécessaire de le recalculer pour maintenir un niveau de sécurité optimal.
 * 
 * @param string $hash Hash stocké à vérifier
 * @return bool True si un nouveau hachage est nécessaire, false sinon
 */
function needsRehash($hash) {
    return password_needs_rehash($hash, PASSWORD_DEFAULT, ['cost' => 12]);
}
?>
