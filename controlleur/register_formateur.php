<?php
include_once '../controlleur/connexion.php';
include_once '../controlleur/user_management.php';

$success = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifier que tous les champs requis sont présents
    if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['mail']) || 
        empty($_POST['mot_de_passe']) || empty($_POST['confirm_mot_de_passe']) ) {
        $error = "Tous les champs sont obligatoires";
    } 
    // Vérifier que les mots de passe correspondent
    elseif ($_POST['mot_de_passe'] !== $_POST['confirm_mot_de_passe']) {
        $error = "Les mots de passe ne correspondent pas";
    } 
    // Vérifier que l'email n'existe pas déjà
    else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM formateur WHERE Mail = ?");
        $stmt->execute([$_POST['mail']]);
        if ($stmt->fetchColumn() > 0) {
            $error = "Cet email est déjà utilisé";
        } else {
            // Créer le formateur
            $data = [
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'mail' => $_POST['mail'],
                'mot_de_passe' => $_POST['mot_de_passe']
            ];
            
            $formateurId = createFormateur($data);
            if ($formateurId) {
                $success = true;
                // Redirection après 3 secondes
                header("refresh:3;url=../controlleur/login_formateur.php");
            } else {
                $error = "Une erreur est survenue lors de l'inscription";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Formateur</title>
    <link rel="stylesheet" href="../vue/style_index.css">
</head>
<body>
    <div class="login-container">
        <div class="image-section">
            <img src="../uploads/icon/logo_aforp.png" alt="Logo Entreprise" class="login-logo">
        </div>
        <div class="form-section">
            <h1>Inscription Formateur</h1>
            
            <?php if ($success): ?>
                <p class="success">Inscription réussie ! Vous allez être redirigé vers la page de connexion...</p>
            <?php else: ?>
                <?php if ($error): ?>
                    <p class="error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                
                <form action="register_formateur.php" method="POST">
                    <div class="input-group">
                        <label for="nom">Nom :</label>
                        <input type="text" id="nom" name="nom" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="prenom">Prénom :</label>
                        <input type="text" id="prenom" name="prenom" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="mail">Email :</label>
                        <input type="email" id="mail" name="mail" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="mot_de_passe">Mot de passe :</label>
                        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="confirm_mot_de_passe">Confirmer le mot de passe :</label>
                        <input type="password" id="confirm_mot_de_passe" name="confirm_mot_de_passe" required>
                    </div>
                    
                    <button type="submit" class="btn">S'inscrire</button>
                </form>
                
                <p>Déjà inscrit ? <a href="../controlleur/login_formateur.php">Se connecter</a></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
