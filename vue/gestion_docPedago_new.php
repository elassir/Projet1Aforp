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

// Inclut le gestionnaire de session pour vérifier la validité de la session utilisateur
include_once '../controlleur/session_manager.php';

// Vérifie si la session est toujours valide (pas expirée)
if (!isSessionValid()) {
    // Redirige vers la page de connexion avec un message d'expiration
    header('Location: ../vue/index.php?session_expired=1');
    exit;
}

// Inclut les fichiers nécessaires pour le fonctionnement de la page
include_once '../controlleur/connexion.php';           // Connexion à la base de données
include_once '../model/DocumentPedago.php';            // Classe représentant un document pédagogique
include_once '../model/DocumentPedagoRepository.php';  // Classe pour interagir avec la table des documents pédagogiques
include_once '../model/Matiere.php';                   // Classe représentant une matière d'enseignement 
include_once '../model/MatiereRepository.php';         // Classe pour interagir avec la table des matières
include_once '../model/systeme.php';                   // Classe représentant un système technique
include_once '../model/SystemeRepository.php';         // Classe pour interagir avec la table des systèmes
include_once '../controlleur/enregistrerDocPedago.php'; // Code de traitement pour l'ajout de documents

// Récupère l'ID du système concerné depuis l'URL, si disponible
// Exemple: gestion_docPedago.php?systeme_concerne=5 pour voir les documents liés au système n°5
$systeme_concerne = isset($_GET['systeme_concerne']) ? $_GET['systeme_concerne'] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Documents Pédagogiques</title>
    <link rel="stylesheet" href="gestion_doc.css">
    <link rel="stylesheet" href="gestion_docPedago.css">
    <script src="gestion_docPedago.js"></script>
</head>
<body>
    <h1>Gestion des Documents Pédagogiques</h1>
    
    <button onclick="window.location.href='../controlleur/logout.php'" class="logout-button">Déconnexion</button>
    <button onclick="window.location.href='../vue/gestion_systemes.php'" class="back-button">Retour aux Systèmes</button>
    
    <!-- Filtre par système -->
    <div class="filter-section">
        <label for="system-filter-select">Filtrer par système:</label>
        <select id="system-filter-select" onchange="filterBySystem(this.value)">
            <option value="0">Tous les systèmes</option>
            <?php
            $systemeRepository = new SystemeRepository($pdo);
            $systemes = $systemeRepository->findAll();
            foreach ($systemes as $systeme) {
                echo "<option value='" . $systeme->getIdSysteme() . "'";
                if (isset($systeme_concerne) && $systeme_concerne == $systeme->getIdSysteme()) {
                    echo " selected";
                }
                echo ">" . htmlspecialchars($systeme->getNomDuSysteme()) . "</option>";
            }
            ?>
        </select>
    </div>
    
    <?php if ($_SESSION['role'] === 'formateur' || $_SESSION['role'] === 'apprenti'): ?>
        <?php if ($_SESSION['role'] === 'formateur'): ?>
            <button onclick="toggleAddDocSection()" class="add-button">Ajouter un Document Pédagogique</button>
            
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
                    <select id="Systeme_concerne" name="Systeme_concerne" required>
                        <?php
                        foreach ($systemes as $systeme) {
                            echo "<option value='{$systeme->getIdSysteme()}'";
                            if (isset($systeme_concerne) && $systeme_concerne == $systeme->getIdSysteme()) {
                                echo " selected";
                            }
                            echo ">{$systeme->getNomDuSysteme()}</option>";
                        }
                        ?>
                    </select>
                    
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
    <?php endif; ?>
    
    <section id="liste-docs-pedago">
        <h2>Liste des Documents Pédagogiques</h2>
        <div class="docs-pedagogiques-container">
            <?php
            $documentPedagoRepository = new DocumentPedagoRepository($pdo);
            $systemeRepository = new SystemeRepository($pdo);
            $matiereRepository = new MatiereRepository($pdo);
            
            if ($_SESSION['role'] === 'apprenti') {
                $consignes = $documentPedagoRepository->findByType('CONSIGNE');
                $devoirs = $documentPedagoRepository->findByApprenti($_SESSION['user']['id_apprenti']);
                $devoirs = array_filter($devoirs, function($doc) {
                    return $doc->getTypeDocument() === 'DEVOIR';
                });
            } else {
                $consignes = $documentPedagoRepository->findByType('CONSIGNE');
                $devoirs = $documentPedagoRepository->findByType('DEVOIR');
            }
            ?>
            
            <div class="section-consignes">
                <h3>Consignes de travail</h3>
                <?php if (empty($consignes)): ?>
                    <p class="no-document">Aucune consigne disponible</p>
                <?php else: ?>
                    <div class="doc-cards">
                        <?php foreach ($consignes as $consigne): 
                            $systeme = $systemeRepository->findById($consigne->getSystemeConcerne());
                            $matiere = $matiereRepository->findById($consigne->getIdMatiere());
                            $nbDevoirs = ($_SESSION['role'] === 'formateur') ? $documentPedagoRepository->countApprentisForDevoir($consigne->getIdPedagogique()) : 0;
                            $nomFichier = basename($consigne->getDocFile());
                        ?>
                            <div class="doc-card consigne-card" data-system="<?= $systeme ? $systeme->getIdSysteme() : '0' ?>">
                                <div class="doc-icon">📋</div>
                                <div class="doc-info">
                                    <span class="file-name"><?= htmlspecialchars($nomFichier) ?></span>
                                    <p class="doc-date">Date: <?= htmlspecialchars($consigne->getDateDocument()) ?></p>
                                    <p class="doc-system">Système: <?= $systeme ? htmlspecialchars($systeme->getNomDuSysteme()) : 'Non spécifié' ?></p>
                                    <p class="doc-matiere">Matière: <?= $matiere ? htmlspecialchars($matiere->getNomMatiere()) : 'Non spécifiée' ?></p>
                                    <?php if ($_SESSION['role'] === 'formateur'): ?>
                                        <p class="doc-status">Devoirs rendus: <span class="badge"><?= $nbDevoirs ?></span></p>
                                    <?php endif; ?>
                                </div>
                                <a href="../uploads/<?= htmlspecialchars($consigne->getDocFile()) ?>" target="_blank" class="btn-view">Voir le document</a>
                                
                                <?php if ($_SESSION['role'] === 'formateur' && $nbDevoirs > 0): ?>
                                    <div style="text-align: center; padding: 10px;">
                                        <button id="toggle-btn-<?= $consigne->getIdPedagogique() ?>" class="devoirs-rendus-toggle" onclick="toggleDevoirsRendus(<?= $consigne->getIdPedagogique() ?>)">
                                            Voir les <?= $nbDevoirs ?> devoirs rendus
                                        </button>
                                    </div>
                                    
                                    <div id="devoirs-container-<?= $consigne->getIdPedagogique() ?>" class="apprentis-container" style="display: none;">
                                        <h5>Apprentis ayant rendu ce devoir:</h5>
                                        <ul class="apprentis-list">
                                            <?php 
                                            $apprentis = $documentPedagoRepository->getApprentisForDevoir($consigne->getIdPedagogique());
                                            foreach ($apprentis as $apprenti): 
                                            ?>
                                                <li>
                                                    <?= htmlspecialchars($apprenti['Nom'] . ' ' . $apprenti['Prenom']) ?> 
                                                    <span class="promotion-badge">Promotion: <?= htmlspecialchars($apprenti['Promotion']) ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="section-devoirs">
                <h3>Devoirs</h3>
                <?php if (empty($devoirs)): ?>
                    <p class="no-document">Aucun devoir disponible</p>
                <?php else: ?>
                    <div class="doc-cards">
                        <?php foreach ($devoirs as $devoir): 
                            $systeme = $systemeRepository->findById($devoir->getSystemeConcerne());
                            $matiere = $matiereRepository->findById($devoir->getIdMatiere());
                            $nomFichier = basename($devoir->getDocFile());
                        ?>
                            <div class="doc-card devoir-card" data-system="<?= $systeme ? $systeme->getIdSysteme() : '0' ?>">
                                <div class="doc-icon">📝</div>
                                <div class="doc-info">
                                    <span class="file-name"><?= htmlspecialchars($nomFichier) ?></span>
                                    <p class="doc-date">Date: <?= htmlspecialchars($devoir->getDateDocument()) ?></p>
                                    <p class="doc-system">Système: <?= $systeme ? htmlspecialchars($systeme->getNomDuSysteme()) : 'Non spécifié' ?></p>
                                    <p class="doc-matiere">Matière: <?= $matiere ? htmlspecialchars($matiere->getNomMatiere()) : 'Non spécifiée' ?></p>
                                    <?php if ($_SESSION['role'] === 'formateur'): ?>
                                        <p class="doc-author">Auteur: 
                                            <?php 
                                            $auteurs = $documentPedagoRepository->getApprentisForDevoir($devoir->getIdPedagogique());
                                            if (!empty($auteurs)) {
                                                echo htmlspecialchars($auteurs[0]['Nom'] . ' ' . $auteurs[0]['Prenom']);
                                            } else {
                                                echo 'Non spécifié';
                                            }
                                            ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <a href="../uploads/<?= htmlspecialchars($devoir->getDocFile()) ?>" target="_blank" class="btn-view">Voir le document</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</body>
</html>
