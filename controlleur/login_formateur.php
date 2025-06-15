<?php
session_start();
include_once '../controlleur/connexion.php';
include_once '../controlleur/password_utils.php';
include_once '../controlleur/user_management.php';
include_once '../controlleur/fail2ban.php';  // Inclusion du système fail2ban

// Vérifier si l'adresse IP est bloquée
$ip = $_SERVER['REMOTE_ADDR'];
$blockStatus = isIpBlocked($ip);

if ($blockStatus['is_blocked']) {
    $remainingMinutes = ceil($blockStatus['remaining_time'] / 60);
    $error = "Trop de tentatives de connexion. Veuillez réessayer dans {$remainingMinutes} minutes.";
} 
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mail = $_POST['mail'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM formateur WHERE Mail = ?");
    $stmt->execute([$mail]);
    $formateur = $stmt->fetch(PDO::FETCH_ASSOC);

    // Transition : Vérifier d'abord si le mot de passe est encore en texte clair
    if ($formateur) {
        $authenticated = false;
        
        // Vérifier si le mot de passe est déjà haché
        if (password_get_info($formateur['Mot_de_passe'])['algo'] !== 0) {
            // Le mot de passe est déjà haché, utilisez verifyPassword
            $authenticated = verifyPassword($mot_de_passe, $formateur['Mot_de_passe']);
        } else {
            // Le mot de passe est encore en texte clair, comparaison directe
            if ($mot_de_passe === $formateur['Mot_de_passe']) {
                $authenticated = true;
                
                // Mettre à jour le mot de passe en version hachée pour les futures connexions
                updateFormateurPassword($formateur['id_formateur'], $mot_de_passe);
            }
        }
        
        if ($authenticated) {
            // Réinitialiser les tentatives de connexion
            resetAttempts($ip);
            
            $_SESSION['user'] = $formateur;
            $_SESSION['role'] = 'formateur';
            $_SESSION['last_activity'] = time();  // Enregistre le moment de la connexion pour la gestion de session
            header('Location: ../vue/gestion_systemes.php');
            exit;
        } else {
            // Enregistrer la tentative échouée
            recordFailedAttempt($ip);
        }
    } else {
        // Enregistrer la tentative échouée même si l'email n'existe pas
        recordFailedAttempt($ip);
    }
    
    // Si nous arrivons ici, l'authentification a échoué
    $error = "Identifiants incorrects";
    
    // Afficher le nombre de tentatives restantes si l'IP commence à accumuler des échecs
    if ($blockStatus['attempts'] > 0) {
        $remaining = MAX_LOGIN_ATTEMPTS - $blockStatus['attempts'];
        if ($remaining > 0) {
            $error .= ". Il vous reste {$remaining} tentative(s) avant blocage temporaire.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Formateur</title>
    <link rel="stylesheet" href="../vue/style_index.css">
</head>
<body>
    <div class="login-container">
        <div class="image-section">
            <img src="../uploads/logo_aforp.png" alt="Logo Entreprise" class="login-logo">
        </div>
        <div class="form-section">
            <h1>Connexion Formateur</h1>
            <?php if (isset($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form action="login_formateur.php" method="POST">
                <label for="mail">Email :</label>
                <input type="email" id="mail" name="mail" required>
                <label for="mot_de_passe">Mot de passe :</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                <button type="submit">Se connecter</button>
            </form>
            <p>Pas encore inscrit ? <a href="../controlleur/register_formateur.php">Créer un compte</a></p>
        </div>
    </div>
</body>
</html>