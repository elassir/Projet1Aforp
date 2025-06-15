<?php
// Démarre la session pour maintenir l'état de connexion de l'utilisateur
session_start();
// Vérifie si l'utilisateur est connecté, sinon le redirige vers la page d'accueil
if (!isset($_SESSION['user'])) {
    header('Location: ../vue/index.php');
    exit;
}
// Inclut les fichiers nécessaires pour la connexion à la base de données et les classes des objets
include_once '../controlleur/connexion.php';
include_once '../model/SystemeRepository.php';
include_once '../model/fabriquant.php';
include_once '../model/fabriquantRepository.php';

// Affiche les messages de succès ou d'erreur si présents
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
        // Fonction JavaScript pour afficher ou masquer la section d'ajout de système
        function toggleAddSystemSection() {
            const section = document.getElementById('ajout-systeme');
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }

        // Fonction pour ouvrir la fenêtre modale des détails d'un système
        function openSystemModal(id_systeme) {
            document.getElementById(`modal-${id_systeme}`).style.display = 'flex';
        }

        // Fonction pour fermer la fenêtre modale des détails d'un système
        function closeSystemModal(id_systeme) {
            document.getElementById(`modal-${id_systeme}`).style.display = 'none';
        }
    </script>
</head>
<body>
    <h1>Gestion des Systèmes</h1>
    <!-- Bouton de déconnexion -->
    <button onclick="window.location.href='../controlleur/logout.php'" class="logout-button">Déconnexion</button>
    
    <?php if ($_SESSION['role'] === 'formateur'): ?>
        <!-- Cette section est visible uniquement pour les formateurs -->
        
        <!-- Bouton pour afficher/masquer le formulaire d'ajout de système -->
        <button onclick="toggleAddSystemSection()" class="add-button">Ajouter un Système</button>
        
        <!-- Formulaire d'ajout de système (masqué par défaut) -->
        <section id="ajout-systeme" style="display: none;">
            <h2>Ajouter un Nouveau Système</h2>
            <form action="../controlleur/enregistrerSysteme.php" method="POST" enctype="multipart/form-data">
                <!-- Champ pour le nom du système -->
                <label for="Nom">Nom du système :</label>
                <input type="text" id="Nom" name="Nom" required>
                
                <!-- Champ pour la date de mise à jour -->
                <label for="date_mise_a_jour">Date de dernière mise à jour :</label>
                <input type="date" id="date_mise_a_jour" name="date_mise_a_jour" required>
                
                <!-- Champ pour l'image du système -->
                <label for="image_systeme">Image du système :</label>
                <input type="file" id="image_systeme" name="image_systeme" accept="image/*" >
                
                <!-- Champ pour le numéro de série -->
                <label for="Numero_de_serie">Numéro de série :</label>
                <input type="text" id="Numero_de_serie" name="Numero_de_serie" required>
                
                <!-- Liste déroulante pour choisir un fabricant -->
                <label for="Fabriquant">Fabriquant :</label>
                <select id="Fabriquant" name="Fabriquant" required>
                    <option value="">Sélectionnez un fabricant</option>
                    <?php
                    // Récupère tous les fabricants de la base de données
                    $fabriquantRepository = new FabriquantRepository($pdo);
                    $fabricants = $fabriquantRepository->findAll();
                    
                    // Si aucun fabricant n'est trouvé, affiche un message
                    if (empty($fabricants)): ?>
                        <option value="" disabled>Aucun fabricant disponible</option>
                    <?php else:
                        // Sinon, crée une option pour chaque fabricant
                        foreach ($fabricants as $fabricant): ?>
                            <option value="<?= htmlspecialchars($fabricant->getSiret()); ?>">
                                <?= htmlspecialchars($fabricant->getNom()); ?> (SIRET: <?= htmlspecialchars($fabricant->getSiret()); ?>)
                            </option>
                        <?php endforeach;
                    endif; ?>
                </select>
                
                <!-- Lien pour ajouter un nouveau fabricant -->
                <p style="font-size: 0.9em; color: #666; margin-top: 5px;">
                    <a href="gestion_fabricants.php" target="_blank" style="color: #007bff; text-decoration: none;">
                        ➕ Ajouter un nouveau fabricant
                    </a>
                </p>
                
                <!-- Champ pour la date de fabrication -->
                <label for="Date_fabrication">Date de fabrication :</label>
                <input type="date" id="Date_fabrication" name="Date_fabrication" required>
                
                <!-- Zone de texte pour la description -->
                <label for="Description">Description :</label>
                <textarea id="Description" name="Description" rows="4" required></textarea>
                
                <button type="submit">Ajouter le Système</button>
            </form>
        </section>
    <?php endif; ?>
    
    <!-- Section listant tous les systèmes -->
    <section id="liste-systemes">
        <h2>Liste des Systèmes</h2>
        <div class="systemes-container">
            <?php
            // Récupère tous les systèmes de la base de données
            $systemeRepository = new SystemeRepository($pdo);
            $systemes = $systemeRepository->findAll();
            $fabriquantRepository = new FabriquantRepository($pdo);

            // Pour chaque système récupéré, affiche une carte
            foreach ($systemes as $systeme):
                // Récupère les informations du fabricant associé au système
                $fabriquant = $fabriquantRepository->findBySiret($systeme->getFabriquant());
            ?>
                <!-- Carte affichant les informations d'un système -->
                <div class="systeme-card">
                    <h3><?= htmlspecialchars($systeme->getNomDuSysteme()); ?></h3>
                    
                    <!-- Image du système qui ouvre une modale quand on clique dessus -->
                    <img src="../uploads/<?= htmlspecialchars($systeme->getImageSysteme()); ?>" 
                         alt="<?= htmlspecialchars($systeme->getNomDuSysteme()); ?>" 
                         class="systeme-image" 
                         onclick="openSystemModal(<?= $systeme->getIdSysteme(); ?>)">
                    
                    <p><strong>Numéro de série :</strong> <?= htmlspecialchars($systeme->getNumeroDeSerie()); ?></p>
                    
                    <!-- Fenêtre modale contenant les détails du système (masquée par défaut) -->
                    <div id="modal-<?= $systeme->getIdSysteme(); ?>" class="modal">
                        <div class="modal-content">
                            <!-- Bouton pour fermer la modale -->
                            <span class="close" onclick="closeSystemModal(<?= $systeme->getIdSysteme(); ?>)">&times;</span>
                            
                            <h3>Détails du système : <?= htmlspecialchars($systeme->getNomDuSysteme()); ?></h3>
                            <p><strong>Fabriquant :</strong> <?= htmlspecialchars($fabriquant->getNom()); ?></p>
                            <p><strong>Date de fabrication :</strong> <?= htmlspecialchars($systeme->getDateFabrication()); ?></p>
                            <p><strong>Tel :</strong> <?= htmlspecialchars($fabriquant->getTel()); ?></p>
                            <p><strong>Adresse :</strong> <?= htmlspecialchars($fabriquant->getAdresse()); ?></p>
                            
                            <!-- Boutons pour accéder aux documents associés au système -->
                            <button class="doc-technique-button" 
                                    onclick="window.location.href='../vue/gestion_doc.php?systeme_concerne=<?= $systeme->getIdSysteme(); ?>'">
                                Document technique
                            </button>
                            <button class="doc-pedago-button" 
                                    onclick="window.location.href='../vue/gestion_docPedago.php?systeme_concerne=<?= $systeme->getIdSysteme(); ?>'">
                                Document pédagogique
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>
