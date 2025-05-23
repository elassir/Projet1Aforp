<?php
session_start();
include_once '../controlleur/connexion.php';
include_once '../controlleur/password_utils.php';
include_once '../controlleur/user_management.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
            $_SESSION['user'] = $formateur;
            $_SESSION['role'] = 'formateur';
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
    <title>Connexion Formateur</title>
    <link rel="stylesheet" href="../vue/style_index.css">
</head>
<body>
    <h1>Connexion Formateur</h1>
    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>    <form action="login_formateur.php" method="POST">
        <label for="mail">Email :</label>
        <input type="email" id="mail" name="mail" required>
        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
        <button type="submit">Se connecter</button>
    </form>
    <p>Pas encore inscrit ? <a href="../controlleur/register_formateur.php">Créer un compte</a></p>
</body>
</html>