<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../vue/index.php');
    exit;
}
include_once '../controlleur/connexion.php';
include_once '../model/SystemeRepository.php';
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
    <title>Gestion des Systèmes</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Affiche ou masque la section d'ajout de système
        function toggleAddSystemSection() {
            const section = document.getElementById('ajout-systeme');
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }

        // Déconnexion automatique lors de la fermeture de la page
       

        // Affiche ou masque la modale pour les détails du système
        function openSystemModal(id_systeme) {
            document.getElementById(`modal-${id_systeme}`).style.display = 'flex';
        }

        function closeSystemModal(id_systeme) {
            document.getElementById(`modal-${id_systeme}`).style.display = 'none';
        }
    </script>
</head>
<body>
    <h1>Gestion des Systèmes</h1>
    <button onclick="window.location.href='../controlleur/logout.php'" class="logout-button">Déconnexion</button>
    <?php if ($_SESSION['role'] === 'formateur'): ?>
        <!-- Bouton pour afficher le formulaire d'ajout de système -->
        <button onclick="toggleAddSystemSection()" class="add-button">Ajouter un Système</button>
        <!-- Formulaire d'ajout de système masqué par défaut -->
        <section id="ajout-systeme" style="display: none;">
            <h2>Ajouter un Nouveau Système</h2>
            <form action="../controlleur/enregistrerSysteme.php" method="POST" enctype="multipart/form-data">
                <label for="Nom">Nom du système :</label>
                <input type="text" id="Nom" name="Nom" required>
                <label for="date_mise_a_jour">Date de dernière mise à jour :</label>
                <input type="date" id="date_mise_a_jour" name="date_mise_a_jour" required>
                <label for="image_systeme">Image du système :</label>
                <input type="file" id="image_systeme" name="image_systeme" accept="image/*" >                <label for="Numero_de_serie">Numéro de série :</label>
                <input type="text" id="Numero_de_serie" name="Numero_de_serie" required>                <label for="Fabriquant">Fabriquant :</label>
                <select id="Fabriquant" name="Fabriquant" required>
                    <option value="">Sélectionnez un fabricant</option>
                    <?php
                    $fabriquantRepository = new FabriquantRepository($pdo);
                    $fabricants = $fabriquantRepository->findAll();
                    if (empty($fabricants)): ?>
                        <option value="" disabled>Aucun fabricant disponible</option>
                    <?php else:
                        foreach ($fabricants as $fabricant): ?>
                            <option value="<?= htmlspecialchars($fabricant->getSiret()); ?>">
                                <?= htmlspecialchars($fabricant->getNom()); ?> (SIRET: <?= htmlspecialchars($fabricant->getSiret()); ?>)
                            </option>
                        <?php endforeach;
                    endif; ?>
                </select>
                <p style="font-size: 0.9em; color: #666; margin-top: 5px;">
                    <a href="gestion_fabricants.php" target="_blank" style="color: #007bff; text-decoration: none;">
                        ➕ Ajouter un nouveau fabricant
                    </a>
                </p>
                <label for="Date_fabrication">Date de fabrication :</label>
                <input type="date" id="Date_fabrication" name="Date_fabrication" required>
                <label for="Description">Description :</label>
                <textarea id="Description" name="Description" rows="4" required></textarea>
                <button type="submit">Ajouter le Système</button>
            </form>
        </section>
    <?php endif; ?>
    <!-- Liste des systèmes existants -->
    <section id="liste-systemes">
        <h2>Liste des Systèmes</h2>
        <div class="systemes-container">
            <?php
            $systemeRepository = new SystemeRepository($pdo);
            $systemes = $systemeRepository->findAll();
            $fabriquantRepository = new FabriquantRepository($pdo);

            foreach ($systemes as $systeme):
                $fabriquant = $fabriquantRepository->findBySiret($systeme->getFabriquant());
            ?>
                <div class="systeme-card">
                    <h3><?= htmlspecialchars($systeme->getNomDuSysteme()); ?></h3>
                    <!-- Image du système avec lien pour ouvrir la modale de détails -->
                    <img src="../uploads/<?= htmlspecialchars($systeme->getImageSysteme()); ?>" alt="<?= htmlspecialchars($systeme->getNomDuSysteme()); ?>" class="systeme-image" onclick="openSystemModal(<?= $systeme->getIdSysteme(); ?>)">
                    <p><strong>Numéro de série :</strong> <?= htmlspecialchars($systeme->getNumeroDeSerie()); ?></p>
                    <!-- Modale pour afficher les détails du système -->
                    <div id="modal-<?= $systeme->getIdSysteme(); ?>" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeSystemModal(<?= $systeme->getIdSysteme(); ?>)">&times;</span>
                            <h3>Détails du système : <?= htmlspecialchars($systeme->getNomDuSysteme()); ?></h3>
                            <p><strong>Fabriquant :</strong> <?= htmlspecialchars($fabriquant->getNom()); ?></p>
                            <p><strong>Date de fabrication :</strong> <?= htmlspecialchars($systeme->getDateFabrication()); ?></p>
                            <p><strong>Tel :</strong> <?= htmlspecialchars($fabriquant->getTel()); ?></p>
                            <p><strong>Adresse :</strong> <?= htmlspecialchars($fabriquant->getAdresse()); ?></p>
                            <button class="doc-technique-button" onclick="window.location.href='../vue/gestion_doc.php?systeme_concerne=<?= $systeme->getIdSysteme(); ?>'">Document technique</button>
                            <button class="doc-pedago-button" onclick="window.location.href='../vue/gestion_docPedago.php?systeme_concerne=<?= $systeme->getIdSysteme(); ?>'">Document pédagogique</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>
