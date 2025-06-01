<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'formateur') {
    header('Location: ../vue/index.php');
    exit;
}
include_once '../controlleur/connexion.php';
include_once '../model/fabriquant.php';
include_once '../model/fabriquantRepository.php';

// Affiche les messages de succès ou d'erreur, le cas échéant
if (isset($message)) {
    echo "<p class='message'>$message</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Fabricants</title>
    <link rel="stylesheet" href="styles.css">    <script>
        // Affiche ou masque la section d'ajout de fabricant
        function toggleAddFabricantSection() {
            const section = document.getElementById('ajout-fabricant');
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }

        // Fonction pour notifier la fenêtre parente si cette page est ouverte dans un nouvel onglet
        window.addEventListener('beforeunload', function() {
            if (window.opener && !window.opener.closed) {
                try {
                    window.opener.location.reload();
                } catch (e) {
                    // Gestion silencieuse de l'erreur si l'accès est bloqué
                }
            }
        });
    </script>
</head>
<body>
    <h1>Gestion des Fabricants</h1>
    <button onclick="window.location.href='../controlleur/logout.php'" class="logout-button">Déconnexion</button>
    <button onclick="window.location.href='gestion_systemes.php'" class="back-button">Retour aux Systèmes</button>
    
    <!-- Bouton pour afficher le formulaire d'ajout de fabricant -->
    <button onclick="toggleAddFabricantSection()" class="add-button">Ajouter un Fabricant</button>
    
    <!-- Formulaire d'ajout de fabricant masqué par défaut -->
    <section id="ajout-fabricant" style="display: none;">
        <h2>Ajouter un Nouveau Fabricant</h2>
        <form action="../controlleur/enregistrerFabriquant.php" method="POST">
            <label for="Nom">Nom du fabricant :</label>
            <input type="text" id="Nom" name="Nom" required>
            <label for="Siret">SIRET :</label>
            <input type="text" id="Siret" name="Siret" pattern="[0-9]{14}" title="Le SIRET doit contenir 14 chiffres" required>
            <label for="Tel">Téléphone :</label>
            <input type="tel" id="Tel" name="Tel" required>
            <label for="Adresse">Adresse :</label>
            <input type="text" id="Adresse" name="Adresse" required>
            <button type="submit">Ajouter le Fabricant</button>
        </form>
    </section>
    
    <!-- Liste des fabricants existants -->
    <section id="liste-fabricants">
        <h2>Liste des Fabricants</h2>
        <div class="fabricants-container">
            <?php
            $fabriquantRepository = new FabriquantRepository($pdo);
            $fabricants = $fabriquantRepository->findAll();
            
            if (empty($fabricants)): ?>
                <p>Aucun fabricant trouvé. Ajoutez un fabricant pour commencer.</p>
            <?php else: ?>
                <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                    <thead>
                        <tr style="background-color: #f2f2f2;">
                            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Nom</th>
                            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">SIRET</th>
                            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Téléphone</th>
                            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Adresse</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fabricants as $fabricant): ?>
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 12px;"><?= htmlspecialchars($fabricant->getNom()); ?></td>
                                <td style="border: 1px solid #ddd; padding: 12px;"><?= htmlspecialchars($fabricant->getSiret()); ?></td>
                                <td style="border: 1px solid #ddd; padding: 12px;"><?= htmlspecialchars($fabricant->getTel()); ?></td>
                                <td style="border: 1px solid #ddd; padding: 12px;"><?= htmlspecialchars($fabricant->getAdresse()); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>
