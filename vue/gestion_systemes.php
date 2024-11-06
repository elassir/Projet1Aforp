<?php
// views/gestion_systemes.php
include_once '../controlleur/connexion.php';
include_once '../model/systemeRepository.php';
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
    <title>Gestion des Systèmes</title>
    <link rel="stylesheet" href="/projet1.1 copy/vue/styles.css">
    <script>
        // Affiche ou masque la section d'ajout de système
        function toggleAddSystemSection() {
            const section = document.getElementById('ajout-systeme');
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }

        // Affiche ou masque la modale pour les détails du système
        function openSystemModal(systemId) {
            document.getElementById(`modal-${systemId}`).style.display = 'block';
        }
        
        function closeSystemModal(systemId) {
            document.getElementById(`modal-${systemId}`).style.display = 'none';
        }
    </script>
</head>
<body>
    <h1>Gestion des Systèmes</h1>

    <!-- Bouton pour afficher le formulaire d'ajout de système -->
    <button onclick="toggleAddSystemSection()" class="add-button">Ajouter un Système</button>

    <!-- Formulaire d'ajout de système masqué par défaut -->
    <section id="ajout-systeme" style="display: none;">
        <h2>Ajouter un Nouveau Système</h2>
        <form action="../controlleur/enregistrerSysteme.php" method="POST" enctype="multipart/form-data">
            <label for="Nom">Nom du système :</label>
            <input type="text" id="Nom" name="Nom" required>

            <label for="date_derniere_mise_a_jour">Date de dernière mise à jour :</label>
            <input type="date" id="date_mise_a_jour" name="date_mise_a_jour" required>

            <label for="image_systeme">Image du système :</label>
            <input type="file" id="image_systeme" name="image_systeme" accept="image/*" >

            <label for="Numero_de_serie">Numéro de série :</label>
            <input type="text" id="Numero_de_serie" name="Numero_de_serie" required>

            <label for="Fabriquant">Fabriquant :</label>
            <input type="text" id="Fabriquant" name="Fabriquant" required>

            <label for="Date_fabrication">Date de fabrication :</label>
            <input type="Date" id="Date_fabrication" name="Date_fabrication" required>

            <label for="Description">Description :</label>
            <textarea id="Description" name="Description" rows="4" required></textarea>

            <button type="submit">Ajouter le Système</button>
        </form>
    </section>


    <!-- Liste des systèmes existants -->
    <section id="liste-systemes">
        <h2>Liste des Systèmes</h2>
        
        <div class="systemes-container">
            <?php
             
            $systemeRepository = new SystemeRepository($pdo);
            $systemes = $systemeRepository->findAll();
            print_r($systemes);
            foreach ($systemes as $systeme): ?>
                <div class="systeme-card">
                    <h3><?= htmlspecialchars($systeme->getNomDuSysteme()); ?></h3>

                    <!-- Image du système avec lien pour ouvrir la modale de détails -->
                    <img src="../uploads/<?= htmlspecialchars($systeme->getImageSysteme()); ?>" alt="<?= htmlspecialchars($systeme->getNomDuSysteme()); ?>" class="systeme-image" onclick="openSystemModal(<?= $systeme->getIdSysteme(); ?>)">

                    <p><strong>Numéro de série :</strong> <?= htmlspecialchars($systeme->getNumeroDeSerie()); ?></p>

                    <!-- Modale pour afficher les versions et les infos du fournisseur -->
                    <div id="modal-<?= $systeme->getIdSysteme(); ?>" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeSystemModal(<?= $systeme->getIdSysteme(); ?>)">&times;</span>
                            <h3>Détails du système : <?= htmlspecialchars($systeme->getNomDuSysteme()); ?></h3>
                            <p><strong>Fabriquant :</strong> <?= htmlspecialchars($systeme->getFabriquant()); ?></p>
                             <p><strong>Versions :</strong></p>
                            <ul>
                                <?php foreach ($systeme->getVersions() as $version): ?>
                                    <li><?= htmlspecialchars($version->getNumeroVersion()) ?> - <?= htmlspecialchars($version->getDateSortie()) ?></li>
                                <?php endforeach; ?>
                            </ul> 
                            <p><strong>Informations du fournisseur :</strong></p>
                            <p>Nom : <?= htmlspecialchars($fournisseurInfo->getNom()) ?></p>
                            <p>Tel : <?= htmlspecialchars($fournisseurInfo->getTel()) ?></p>
                            <p>Email : <?= htmlspecialchars($fournisseurInfo->getEmail()) ?></p>
                            
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>
