<?php
/**
 * Fichier: gestion_docPedago.php
 * 
 * Cette page permet de gérer les documents pédagogiques :
 * - Consultation des documents (pour tous les utilisateurs)
 * - Ajout de nouveaux documents (pour les formateurs)
 * 
 * Les documents pédagogiques sont des ressources partagées entre formateurs et apprentis
 * comme des cours, des devoirs, des consignes de travail, etc.
 */

// Démarre la session pour maintenir l'état de connexion de l'utilisateur
// Cette ligne permet d'accéder aux variables de session comme $_SESSION['user'] et $_SESSION['role']
session_start();

// Inclut les fichiers nécessaires pour le fonctionnement de la page
include_once '../controlleur/connexion.php';           // Connexion à la base de données
include_once '../model/DocumentPedago.php';            // Classe représentant un document pédagogique
include_once '../model/DocumentPedagoRepository.php';  // Classe pour interagir avec la table des documents pédagogiques
include_once '../model/Matiere.php';                   // Classe représentant une matière d'enseignement 
include_once '../model/MatiereRepository.php';         // Classe pour interagir avec la table des matières
include_once '../controlleur/enregistrerDocPedago.php'; // Code de traitement pour l'ajout de documents

// Récupère l'ID du système concerné depuis l'URL, si disponible
// Exemple: gestion_docPedago.php?systeme_concerne=5 pour voir les documents liés au système n°5
$systeme_concerne = isset($_GET['systeme_concerne']) ? $_GET['systeme_concerne'] : null;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">                                  <!-- Définit l'encodage du document -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Optimisation pour appareils mobiles -->
    <title>Gestion des Documents Pédagogiques</title>       <!-- Titre de la page qui s'affiche dans l'onglet du navigateur -->
    <link rel="stylesheet" href="gestion_doc.css">          <!-- Lien vers la feuille de style CSS -->
    <script>
        /**
         * Fonction JavaScript qui affiche ou masque le formulaire d'ajout de document
         * 
         * Cette fonction est appelée lorsque l'utilisateur clique sur le bouton "Ajouter un Document Pédagogique"
         * Elle permet de faire apparaître ou disparaître le formulaire sans recharger la page
         */
        function toggleAddDocSection() {
            // Récupère la section contenant le formulaire par son identifiant
            const section = document.getElementById('ajout-doc-pedago');
            
            // Change sa propriété d'affichage : si elle est cachée, l'affiche, et inversement
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <!-- Titre principal de la page -->
    <h1>Gestion des Documents Pédagogiques</h1>
    
    <!-- Bouton permettant à l'utilisateur de se déconnecter -->
    <!-- Lors du clic, le navigateur redirige vers le script de déconnexion -->
    <button onclick="window.location.href='../controlleur/logout.php'" class="logout-button">Déconnexion</button>
      <?php if ($_SESSION['role'] === 'formateur' || $_SESSION['role'] === 'apprenti'): ?>
        <!-- Cette section est accessible à la fois aux formateurs et aux apprentis -->
        
        <!-- Bouton permettant d'afficher ou de masquer le formulaire d'ajout -->
        <!-- Lorsqu'on clique dessus, la fonction JavaScript toggleAddDocSection() est appelée -->
        <button onclick="toggleAddDocSection()" class="add-button">Ajouter un Document Pédagogique</button>
        
        <!-- Section contenant le formulaire d'ajout (invisible par défaut) -->
        <section id="ajout-doc-pedago" style="display: none;">
            <h2>Ajouter un Nouveau Document Pédagogique</h2>
            
            <!-- Formulaire qui enverra les données au contrôleur enregistrerDocPedago.php -->
            <!-- enctype="multipart/form-data" est nécessaire pour l'envoi de fichiers -->
            <form action="../controlleur/enregistrerDocPedago.php" method="POST" enctype="multipart/form-data">
                
                <!-- Liste déroulante pour choisir la matière concernée par le document -->
                <label for="id_matiere">Matière :</label>
                <select id="id_matiere" name="id_matiere" required>
                    <?php
                    // Crée un objet qui permet d'accéder aux matières en base de données
                    $matiereRepository = new MatiereRepository($pdo);
                    
                    // Récupère toutes les matières disponibles
                    $matieres = $matiereRepository->findAll();
                    
                    // Génère une option dans le menu déroulant pour chaque matière
                    foreach ($matieres as $matiere) {
                        // La valeur envoyée sera l'ID de la matière, mais l'utilisateur voit son nom
                        echo "<option value='{$matiere->getIdMatiere()}'>{$matiere->getNomMatiere()}</option>";
                    }
                    ?>
                </select>
                
                <!-- Champ de saisie pour le système concerné par le document -->
                <label for="Systeme_concerne">Système concerné :</label>
                <input type="text" id="Systeme_concerne" name="Systeme_concerne" required>
                
                <!-- Sélecteur de date pour indiquer la date du document -->
                <label for="Date_Document">Date du document :</label>
                <input type="date" id="Date_Document" name="Date_Document" required>
                
                <!-- Liste déroulante pour choisir le type de document (options prédéfinies) -->
                <label for="Type_document">Type de document :</label>
                <select id="Type_document" name="Type_document" required>
                    <option value="DEVOIR">Devoir</option>       <!-- Travail à réaliser par les apprentis -->
                    <option value="CONSIGNE">Consigne</option>   <!-- Instructions ou directives -->
                </select>
                
                <!-- Champ pour sélectionner et télécharger un fichier -->
                <label for="Doc_file">Fichier :</label>
                <input type="file" id="Doc_file" name="Doc_file" required>
                
                <!-- Bouton pour soumettre le formulaire -->
                <button type="submit">Ajouter le Document Pédagogique</button>
            </form>
        </section>
    <?php endif; ?>
      <!-- Section qui affiche la liste de tous les documents pédagogiques disponibles -->
    <section id="liste-docs-pedago">
        <h2>Liste des Documents Pédagogiques</h2>
        <div class="docs-techniques-container">
            <?php
            // Créer un objet pour accéder aux documents en base de données
            $documentPedagoRepository = new DocumentPedagoRepository($pdo);
            
            // Le contenu affiché dépend du rôle de l'utilisateur connecté
            if ($_SESSION['role'] === 'apprenti') {
                // Si c'est un apprenti, il ne voit que les documents qui lui sont assignés
                // Cette restriction de sécurité empêche les apprentis de voir tous les documents
                $documents = $documentPedagoRepository->findByApprenti($_SESSION['user']['id_apprenti']);
            } else {
                // Si c'est un formateur, il peut voir tous les documents pédagogiques
                $documents = $documentPedagoRepository->findAll();
            }
            
            // Organisation des documents par type pour un affichage structuré
            // On crée un tableau avec deux catégories: DEVOIR et CONSIGNE
            $types = [
                'DEVOIR' => [],    // Tableau qui contiendra tous les devoirs
                'CONSIGNE' => []   // Tableau qui contiendra toutes les consignes
            ];
            
            // Pour chaque document récupéré, on l'ajoute dans la catégorie correspondante
            foreach ($documents as $document) {
                $types[$document->getTypeDocument()][] = $document;
            }
            ?>
            
            <!-- Disposition en colonnes pour l'affichage -->
            <div class="row">
                <?php foreach ($types as $type => $docs): ?>
                    <!-- Chaque type de document est dans une colonne séparée -->
                    <div class="column">
                        <!-- Titre de la colonne (DEVOIR ou CONSIGNE) -->
                        <h3><?= htmlspecialchars($type) ?></h3>
                        
                        <!-- Liste des documents de ce type -->
                        <ul>
                            <?php foreach ($docs as $doc): ?>
                                <li>
                                    <!-- Lien vers le fichier du document qui s'ouvre dans un nouvel onglet -->
                                    <!-- target="_blank" fait en sorte que le document s'ouvre dans un nouvel onglet -->
                                    <a href="../uploads/<?= htmlspecialchars($doc->getDocFile()) ?>" target="_blank">
                                        <!-- L'utilisateur voit la date du document comme texte du lien -->
                                        <?= htmlspecialchars($doc->getDateDocument()) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</body>
</html>