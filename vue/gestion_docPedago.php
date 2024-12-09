<?php
session_start(); // Ajoutez cette ligne pour démarrer la session
include_once '../controlleur/connexion.php';
include_once '../model/DocumentPedago.php';
include_once '../model/DocumentPedagoRepository.php';
include_once '../model/Matiere.php';
include_once '../model/MatiereRepository.php';
include_once '../controlleur/enregistrerDocPedago.php';

$systeme_concerne = isset($_GET['systeme_concerne']) ? $_GET['systeme_concerne'] : null;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Documents Pédagogiques</title>
    <link rel="stylesheet" href="/projet1.1 copy/vue/gestion_doc.css">
    <script>
        // Affiche ou masque la section d'ajout de document pédagogique
        function toggleAddDocSection() {
            const section = document.getElementById('ajout-doc-pedago');
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <h1>Gestion des Documents Pédagogiques</h1>
    <?php if ($_SESSION['role'] === 'formateur'): ?>
        <!-- Bouton pour afficher le formulaire d'ajout de document pédagogique -->
        <button onclick="toggleAddDocSection()" class="add-button">Ajouter un Document Pédagogique</button>
        <!-- Formulaire d'ajout de document pédagogique masqué par défaut -->
        <section id="ajout-doc-pedago" style="display: none;">
            <h2>Ajouter un Nouveau Document Pédagogique</h2>
            <form action="../controlleur/enregistrerDocPedago.php" method="POST" enctype="multipart/form-data">
                <label for="id_matiere">Matière :</label>
                <select id="id_matiere" name="id_matiere" required>
                    <?php
                    $matiereRepository = new MatiereRepository($pdo);
                    $matieres = $matiereRepository->findAll();
                    foreach ($matieres as $matiere) {
                        echo "<option value='{$matiere->getIdMatiere()}'>{$matiere->getNomMatiere()}</option>";
                    }
                    ?>
                </select>
                <label for="Systeme_concerne">Système concerné :</label>
                <input type="text" id="Systeme_concerne" name="Systeme_concerne" required>
                <label for="Date_Document">Date du document :</label>
                <input type="date" id="Date_Document" name="Date_Document" required>
                <label for="Type_document">Type de document :</label>
                <select id="Type_document" name="Type_document" required>
                    <option value="DEVOIR">Devoir</option>
                    <option value="CONSIGNE">Consigne</option>
                </select>
                <label for="Doc_file">Fichier :</label>
                <input type="file" id="Doc_file" name="Doc_file" required>
                <button type="submit">Ajouter le Document Pédagogique</button>
            </form>
        </section>
    <?php endif; ?>
    <!-- Liste des documents pédagogiques existants -->
    <section id="liste-docs-pedago">
        <h2>Liste des Documents Pédagogiques</h2>
        <div class="docs-techniques-container">
            <?php
            $documentPedagoRepository = new DocumentPedagoRepository($pdo);
            if ($_SESSION['role'] === 'apprenti') {
                $documents = $documentPedagoRepository->findByApprenti($_SESSION['user']['id_apprenti']);
            } else {
                $documents = $documentPedagoRepository->findAll();
            }
            // Trier les documents par type
            $types = [
                'DEVOIR' => [],
                'CONSIGNE' => []
            ];
            foreach ($documents as $document) {
                $types[$document->getTypeDocument()][] = $document;
            }
            ?>
            <div class="row">
                <?php foreach ($types as $type => $docs): ?>
                    <div class="column">
                        <h3><?= htmlspecialchars($type) ?></h3>
                        <ul>
                            <?php foreach ($docs as $doc): ?>
                                <li><?= htmlspecialchars($doc->getNomMatiere()) ?> - <?= htmlspecialchars($doc->getDateDocument()) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</body>
</html>