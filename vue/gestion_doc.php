<?php
// Démarre la session pour maintenir l'état de connexion de l'utilisateur
session_start();
// Inclut le gestionnaire de session
include_once '../controlleur/session_manager.php';

// Vérifie si la session est toujours valide (pas expirée)
if (!isSessionValid()) {
    // Redirige vers la page de connexion avec un message d'expiration
    header('Location: ../vue/index.php?session_expired=1');
    exit;
}

// Vérifie si l'utilisateur est connecté, sinon le redirige vers la page d'accueil
if (!isset($_SESSION['user'])) {
    header('Location: ../vue/index.php');
    exit;
}
// Inclut les fichiers nécessaires pour la connexion à la base de données et les classes des objets
include_once '../controlleur/connexion.php';
include_once '../model/DocumentTechnique.php';
include_once '../model/DocumentTechniqueRepository.php';
include_once '../model/systeme.php';
include_once '../model/SystemeRepository.php';
include_once '../controlleur/enregistrerDocTec.php'; // Contrôleur pour enregistrer les documents techniques

// Récupère l'ID du système concerné depuis l'URL, si disponible
$systeme_concerne = isset($_GET['systeme_concerne']) ? $_GET['systeme_concerne'] : null;

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
    <title>Gestion des Documents Techniques</title>
    <link rel="stylesheet" href="gestion_doc.css">
    <script>
        // Fonction JavaScript pour afficher ou masquer la section d'ajout de document technique
        function toggleAddDocSection() {
            const section = document.getElementById('ajout-doc-technique');
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <h1>Gestion des Documents Techniques</h1>
    <!-- Bouton de déconnexion -->
    <button onclick="window.location.href='../controlleur/logout.php'" class="logout-button">Déconnexion</button>
    
    <?php if ($_SESSION['role'] === 'formateur'): ?>
        <!-- Cette section est visible uniquement pour les formateurs -->
        
        <!-- Bouton pour afficher/masquer le formulaire d'ajout de document technique -->
        <button onclick="toggleAddDocSection()" class="add-button">Ajouter un Document Technique</button>
        
        <!-- Formulaire d'ajout de document technique (masqué par défaut) -->
        <section id="ajout-doc-technique" style="display: none;">
            <h2>Ajouter un Nouveau Document Technique</h2>
            <form action="../controlleur/enregistrerDocTec.php" method="POST" enctype="multipart/form-data">
                <!-- Champ pour le nom du document -->
                <label for="Nom_doc_tech">Nom du document :</label>
                <input type="text" id="Nom_doc_tech" name="Nom_doc_tech" required>
                
                <!-- Champ pour la date du document -->
                <label for="Date">Date :</label>
                <input type="date" id="Date" name="Date" required>
                
                <!-- Liste déroulante pour choisir la catégorie du document -->
                <label for="Categorie">Catégorie :</label>
                <select id="Categorie" name="Categorie" required>
                    <option value="Presentation">Presentation</option>
                    <option value="Annexes">Annexes</option>
                    <option value="Notices">Notices</option>
                    <option value="Schema technique">Schema technique</option>
                </select>
                  <!-- Liste déroulante pour choisir le système concerné par le document -->
                <label for="Systeme_concerne">Système concerné :</label>
                <select id="Systeme_concerne" name="Systeme_concerne" required>
                    <?php
                    // Crée un objet qui permet d'accéder aux systèmes en base de données
                    $systemeRepository = new SystemeRepository($pdo);
                    
                    // Récupère tous les systèmes disponibles
                    $systemes = $systemeRepository->findAll();
                    
                    // Génère une option dans le menu déroulant pour chaque système
                    foreach ($systemes as $systeme) {
                        // La valeur envoyée sera l'ID du système, et l'utilisateur voit le nom du système
                        echo "<option value='{$systeme->getIdSysteme()}'" . 
                             (isset($systeme_concerne) && $systeme_concerne == $systeme->getIdSysteme() ? ' selected' : '') . 
                             ">{$systeme->getNomDuSysteme()}</option>";
                    }
                    ?>
                </select>
                
                <!-- Champ pour télécharger le fichier du document -->
                <label for="Doc_file">Fichier du document :</label>
                <input type="file" id="Doc_file" name="Doc_file" accept=".pdf,.doc,.docx" required>
                
                <!-- Champ pour la version du document -->
                <label for="Version">Version :</label>
                <input type="text" id="Version" name="Version" required>
                
                <button type="submit">Ajouter le Document Technique</button>
            </form>
        </section>
    <?php endif; ?>
    
    <!-- Section listant tous les documents techniques -->
    <section id="liste-docs-techniques">
        <h2>Liste des Documents Techniques</h2>
        <div class="docs-techniques-container">
            <?php
            // Récupère les documents techniques selon le système concerné
            $documentTechniqueRepository = new DocumentTechniqueRepository($pdo);
            if ($systeme_concerne) {
                // Si un système est spécifié, ne récupère que les documents de ce système
                $documents = $documentTechniqueRepository->findBySysteme($systeme_concerne);
            } else {
                // Sinon, récupère tous les documents
                $documents = $documentTechniqueRepository->findAll();
            }
            
            // Trie les documents par catégorie
            $categories = [
                'Presentation' => [],
                'Annexes' => [],
                'Notices' => [],
                'Schema technique' => []
            ];
            foreach ($documents as $document) {
                $categories[$document->getCategorie()][] = $document;
            }
            ?>
            <div class="row">
                <?php foreach ($categories as $category => $docs): ?>
                    <div class="column">
                        <h3><?= htmlspecialchars($category); ?></h3>
                        <?php foreach ($docs as $doc): ?>
                            <!-- Carte pour chaque document technique -->
                            <div class="doc-technique-card">
                                <h4><?= htmlspecialchars($doc->getNom_doc_tech()); ?></h4>
                                <p><strong>Date :</strong> <?= htmlspecialchars($doc->getDate()); ?></p>
                                <p><strong>Version :</strong> <?= htmlspecialchars($doc->getVersion()); ?></p>
                                <!-- Lien pour visualiser le document -->
                                <a href="../uploads/<?= htmlspecialchars($doc->getDocFile()); ?>" target="_self">Voir le document</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</body>
</html>
