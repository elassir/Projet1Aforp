<?php
/**
 * Fichier: fail2ban.php
 * 
 * Ce fichier gère un mécanisme de protection contre les attaques par force brute (fail2ban)
 * Il limite le nombre de tentatives de connexion échouées avant de bloquer temporairement une adresse IP
 */

// Nombre maximum de tentatives de connexion autorisées
define('MAX_LOGIN_ATTEMPTS', 5);

// Durée de blocage en secondes (15 minutes)
define('BLOCK_DURATION', 900);

/**
 * Enregistre une tentative de connexion échouée pour une adresse IP
 * 
 * @param string $ip Adresse IP
 * @return void
 */
function recordFailedAttempt($ip) {
    // Récupère ou crée le fichier de stockage des tentatives
    $failedAttemptsFile = __DIR__ . '/../data/failed_logins.json';
    $failedAttempts = [];
    
    // Crée le dossier data s'il n'existe pas
    if (!file_exists(__DIR__ . '/../data')) {
        mkdir(__DIR__ . '/../data', 0755, true);
    }
    
    // Charge les données existantes
    if (file_exists($failedAttemptsFile)) {
        $failedAttempts = json_decode(file_get_contents($failedAttemptsFile), true) ?? [];
    }
    
    // Nettoie les anciennes entrées (plus de BLOCK_DURATION secondes)
    $currentTime = time();
    foreach ($failedAttempts as $attemptIp => $data) {
        if (isset($data['blocked_until']) && $data['blocked_until'] < $currentTime) {
            // La période de blocage est terminée
            unset($failedAttempts[$attemptIp]);
        }
    }
    
    // Ajoute ou met à jour cette IP
    if (!isset($failedAttempts[$ip])) {
        $failedAttempts[$ip] = [
            'attempts' => 1,
            'last_attempt' => $currentTime
        ];
    } else {
        $failedAttempts[$ip]['attempts']++;
        $failedAttempts[$ip]['last_attempt'] = $currentTime;
        
        // Vérifier si le nombre max de tentatives est atteint
        if ($failedAttempts[$ip]['attempts'] >= MAX_LOGIN_ATTEMPTS) {
            $failedAttempts[$ip]['blocked_until'] = $currentTime + BLOCK_DURATION;
        }
    }
    
    // Enregistre les données
    file_put_contents($failedAttemptsFile, json_encode($failedAttempts));
}

/**
 * Vérifie si une adresse IP est bloquée
 * 
 * @param string $ip Adresse IP
 * @return array [is_blocked: bool, remaining_time: int, attempts: int]
 */
function isIpBlocked($ip) {
    $failedAttemptsFile = __DIR__ . '/../data/failed_logins.json';
    $result = [
        'is_blocked' => false,
        'remaining_time' => 0,
        'attempts' => 0
    ];
    
    if (file_exists($failedAttemptsFile)) {
        $failedAttempts = json_decode(file_get_contents($failedAttemptsFile), true) ?? [];
        
        if (isset($failedAttempts[$ip])) {
            $result['attempts'] = $failedAttempts[$ip]['attempts'];
            
            if (isset($failedAttempts[$ip]['blocked_until'])) {
                $currentTime = time();
                if ($failedAttempts[$ip]['blocked_until'] > $currentTime) {
                    $result['is_blocked'] = true;
                    $result['remaining_time'] = $failedAttempts[$ip]['blocked_until'] - $currentTime;
                }
            }
        }
    }
    
    return $result;
}

/**
 * Réinitialise les tentatives de connexion pour une adresse IP
 * À appeler après une connexion réussie
 * 
 * @param string $ip Adresse IP
 * @return void
 */
function resetAttempts($ip) {
    $failedAttemptsFile = __DIR__ . '/../data/failed_logins.json';
    
    if (file_exists($failedAttemptsFile)) {
        $failedAttempts = json_decode(file_get_contents($failedAttemptsFile), true) ?? [];
        
        if (isset($failedAttempts[$ip])) {
            unset($failedAttempts[$ip]);
            file_put_contents($failedAttemptsFile, json_encode($failedAttempts));
        }
    }
}
?>
