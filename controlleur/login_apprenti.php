<?php
session_start();
include_once '../controlleur/connexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mail = $_POST['mail'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM apprenti WHERE Mail = ?");
    $stmt->execute([$mail]);
    $apprenti = $stmt->fetch(PDO::FETCH_ASSOC);

    // Comparer les mots de passe en clair (non hachés)
    if ($apprenti && $mot_de_passe === $apprenti['Mot_de_passe']) {
        $_SESSION['user'] = $apprenti;
        $_SESSION['role'] = 'apprenti';

        // Débogage : Afficher les données de session et le chemin de redirection
        echo '<pre>';
        print_r($_SESSION);
        echo '</pre>';
        echo 'Redirection vers : ../vue/gestion_systemes.php';
        

        header('Location: ../vue/gestion_systemes.php');
        exit;
    } else {
        $error = "Identifiants incorrects";
    }
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
    <h1>Connexion Apprenti</h1>
    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="login_apprenti.php" method="POST">
        <label for="mail">Email :</label>
        <input type="email" id="mail" name="mail" required>
        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>