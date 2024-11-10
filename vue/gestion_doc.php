<?php 

include_once '../controlleur/connexion.php';
include_once '../model/DocumentTechnique.php';
include_once '../model/DocumentTechniqueRepository.php';
include_once '../controlleur/enregistrerDocTec.php'; // Assurez-vous que ce chemin est correct

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
    <title>Gestion des Documents Techniques</title>
    <link rel="stylesheet" href="/projet1.1 copy/vue/gestion_doc.css">
    <script>
        // Affiche ou masque la section d'ajout de document technique
        function toggleAddDocSection() {
            const section = document.getElementById('ajout-doc-technique');
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <h1>Gestion des Documents Techniques</h1>
    <!-- Bouton pour afficher le formulaire d'ajout de document technique -->
    <button onclick="toggleAddDocSection()" class="add-button">Ajouter un Document Technique</button>
    <!-- Formulaire d'ajout de document technique masqué par défaut -->
    <section id="ajout-doc-technique" style="display: none;">
        <h2>Ajouter un Nouveau Document Technique</h2>
        <form action="../controlleur/enregistrerDocTec.php" method="POST" enctype="multipart/form-data">
            <label for="Nom_doc_tech">Nom du document :</label>
            <input type="text" id="Nom_doc_tech" name="Nom_doc_tech" required>
            <label for="Date">Date :</label>
            <input type="date" id="Date" name="Date" required>
            <label for="Categorie">Catégorie :</label>
            <select id="Categorie" name="Categorie" required>
                <option value="Presentation">Presentation</option>
                <option value="Annexes">Annexes</option>
                <option value="Notices">Notices</option>
                <option value="Schema technique">Schema technique</option>
            </select>
            <label for="Systeme_concerne">Système concerné :</label>
            <input type="text" id="Systeme_concerne" name="Systeme_concerne" required>
            <label for="Doc_file">Fichier du document :</label>
            <input type="file" id="Doc_file" name="Doc_file" accept=".pdf,.doc,.docx" required>
            <label for="Version">Version :</label>
            <input type="text" id="Version" name="Version" required>
            <button type="submit">Ajouter le Document Technique</button>
        </form>
    </section>
    <!-- Liste des documents techniques existants -->
    <section id="liste-docs-techniques">
        <h2>Liste des Documents Techniques</h2>
        <div class="docs-techniques-container">
            <?php
            $documentTechniqueRepository = new DocumentTechniqueRepository($pdo);
            $documents = $documentTechniqueRepository->findAll();
            // Trier les documents par catégorie
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
                            <div class="doc-technique-card">
                                <h4><?= htmlspecialchars($doc->getNom_doc_tech()); ?></h4>
                                <p><strong>Date :</strong> <?= htmlspecialchars($doc->getDate()); ?></p>
                                <p><strong>Système concerné :</strong> <?= htmlspecialchars($doc->getSysteme_concerne()); ?></p>
                                <p><strong>Version :</strong> <?= htmlspecialchars($doc->getVersion()); ?></p>
                                <a href="../uploads/<?= htmlspecialchars($doc->getDocFile()); ?>" target="_blank">Voir le document</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</body>
</html>
