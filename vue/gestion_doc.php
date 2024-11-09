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
</head>
<body>
    <h1>Gestion des Documents Techniques</h1>
    <!-- Formulaire d'ajout de document technique -->
    <section id="ajout-doc-technique">
        <h2>Ajouter un Nouveau Document Technique</h2>
        <form action="../controlleur/enregistrerDocTec.php" method="POST" enctype="multipart/form-data">
            <label for="Nom_doc_tech">Nom du document :</label>
            <input type="text" id="Nom_doc_tech" name="Nom_doc_tech" required>
            <label for="Date">Date :</label>
            <input type="date" id="Date" name="Date" required>
            <label for="Categorie">Catégorie :</label>
            <input type="text" id="Categorie" name="Categorie" required>
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
            foreach ($documents as $document): ?>
                <div class="doc-technique-card">
                    <img src="/path/to/file-icon.png" alt="File Icon">
                    <h3><?= htmlspecialchars($document->getNom_doc_tech()); ?></h3>
                    <p><strong>Date :</strong> <?= htmlspecialchars($document->getDate()); ?></p>
                    <p><strong>Catégorie :</strong> <?= htmlspecialchars($document->getCategorie()); ?></p>
                    <p><strong>Système concerné :</strong> <?= htmlspecialchars($document->getSysteme_concerne()); ?></p>
                    <p><strong>Version :</strong> <?= htmlspecialchars($document->getVersion()); ?></p>
                    <a href="../uploads/<?= htmlspecialchars($document->getDocFile()); ?>" target="_blank">Voir le document</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>
