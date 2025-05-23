<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once '../controlleur/connexion.php';
include_once '../controlleur/password_utils.php';
include_once '../controlleur/user_management.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mail = $_POST['mail'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM apprenti WHERE Mail = ?");
    $stmt->execute([$mail]);
    $apprenti = $stmt->fetch(PDO::FETCH_ASSOC);

    // Transition : Vérifier d'abord si le mot de passe est encore en texte clair
    if ($apprenti) {
        $authenticated = false;
        
        // Vérifier si le mot de passe est déjà haché
        if (password_get_info($apprenti['Mot_de_passe'])['algo'] !== 0) {
            // Le mot de passe est déjà haché, utilisez verifyPassword
            $authenticated = verifyPassword($mot_de_passe, $apprenti['Mot_de_passe']);
        } else {
            // Le mot de passe est encore en texte clair, comparaison directe
            if ($mot_de_passe === $apprenti['Mot_de_passe']) {
                $authenticated = true;
                
                // Mettre à jour le mot de passe en version hachée pour les futures connexions
                updateApprentiPassword($apprenti['id_apprenti'], $mot_de_passe);
            }
        }
        
        if ($authenticated) {
            $_SESSION['user'] = $apprenti;
            $_SESSION['role'] = 'apprenti';

            // Assurez-vous qu'aucun contenu n'est envoyé avant la redirection
            header('Location: ../vue/gestion_systemes.php');
            exit;
        }
    }
    
    // Si nous arrivons ici, l'authentification a échoué
    $error = "Identifiants incorrects";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Apprenti</title>
    <link rel="stylesheet" href="../vue/style_index.css">
</head>
<body>
    <div class="login-container">
        <div class="image-section">
            <img src="../uploads/icon/logo_aforp.png" alt="Logo Entreprise" class="login-logo">
        </div>
        <div class="form-section">
            <h1>Connexion Apprenti</h1>
            <?php if (isset($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>            <form action="login_apprenti.php" method="POST">
                <label for="mail">Email :</label>
                <input type="email" id="mail" name="mail" required>
                <label for="mot_de_passe">Mot de passe :</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                <button type="submit">Se connecter</button>
            </form>
            <p>Pas encore inscrit ? <a href="../controlleur/register_apprenti.php">Créer un compte</a></p>
        </div>
    </div>
</body>
</html>