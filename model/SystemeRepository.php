<?php
// Inclut la classe Systeme pour pouvoir l'utiliser dans ce fichier
include_once '../model/systeme.php';

// Classe responsable de la gestion des opérations de base de données pour les systèmes
class SystemeRepository {
    // Variable qui stocke la connexion à la base de données
    private $pdo;

    // Constructeur qui initialise la connexion à la base de données
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Méthode pour sauvegarder un système dans la base de données (création ou mise à jour)
    public function save(Systeme $systeme) {
        try {
            // Démarre une transaction pour garantir l'intégrité des données
            $this->pdo->beginTransaction();
            
            // Récupère le fabricant associé au système
            $Fabriquant = $systeme->getFabriquant();
            
            // Vérifie si le fabricant existe dans la base de données
            $stmtCheck = $this->pdo->prepare("SELECT COUNT(*) FROM fabriquant WHERE Siret = ?");
            $stmtCheck->execute([$Fabriquant]);

            // Si le fabricant n'existe pas, lance une exception
            if ($stmtCheck->fetchColumn() == 0) {
                throw new Exception("Le fabriquant spécifié n'existe pas dans la table 'fabriquant'.");
            }

            // Si le système n'a pas d'ID, c'est une nouvelle entrée à insérer
            if ($systeme->getIdSysteme() == null) {
                // Prépare et exécute la requête d'insertion
                $stmt = $this->pdo->prepare("INSERT INTO systeme (Nom_du_systeme, date_derniere_mise_a_jour, image_systeme, Numero_de_serie, Fabriquant, Date_fabrication, Description) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $systeme->getNomDuSysteme(),
                    $systeme->getDateDerniereMiseAJour(),
                    $systeme->getImageSysteme(),
                    $systeme->getNumeroDeSerie(),
                    $systeme->getFabriquant(),
                    $systeme->getDateFabrication(),
                    $systeme->getDescription()
                ]);
                // Mise à jour de l'ID du système avec celui généré par la base de données
                $systeme->setIdSysteme($this->pdo->lastInsertId());
            } else {
                // Si le système a déjà un ID, c'est une mise à jour
                $stmt = $this->pdo->prepare("UPDATE systeme SET Nom_du_systeme = ?, date_derniere_mise_a_jour = ?, image_systeme = ?, Numero_de_serie = ?, Fabriquant = ?, Date_fabrication = ?, Description = ? WHERE id_systeme = ?");
                $stmt->execute([
                    $systeme->getNomDuSysteme(),
                    $systeme->getDateDerniereMiseAJour(),
                    $systeme->getImageSysteme(),
                    $systeme->getNumeroDeSerie(),
                    $systeme->getFabriquant(),
                    $systeme->getDateFabrication(),
                    $systeme->getDescription(),
                    $systeme->getIdSysteme()
                ]);
            }

            // Valide la transaction si tout s'est bien passé
            $this->pdo->commit();
        } catch (Exception $e) {
            // Annule la transaction en cas d'erreur
            $this->pdo->rollBack();
            // Remonte l'exception pour qu'elle puisse être gérée plus haut
            throw $e;
        }
    }    // Méthode pour récupérer tous les systèmes de la base de données
    public function findAll() {
        // Exécute une requête pour récupérer tous les systèmes
        $stmt = $this->pdo->query("SELECT * FROM systeme");
        $systemes = [];
        
        // Pour chaque ligne de résultat, crée un objet Systeme et l'ajoute au tableau
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $systemes[] = new Systeme(
                $row['Nom_du_systeme'],
                $row['date_derniere_mise_a_jour'],
                $row['image_systeme'],
                $row['Numero_de_serie'],
                $row['Fabriquant'],
                $row['Date_fabrication'],
                $row['Description'],
                $row['id_systeme']
            );
        }
        
        // Retourne tous les systèmes trouvés
        return $systemes;
    }
    
    /**
     * Recherche un système par son ID
     * 
     * @param int $id_systeme L'ID du système recherché
     * @return Systeme|null Le système trouvé ou null si aucun système ne correspond
     */
    public function findById($id_systeme) {
        // Prépare et exécute une requête pour trouver un système par son ID
        $stmt = $this->pdo->prepare("SELECT * FROM systeme WHERE id_systeme = ?");
        $stmt->execute([$id_systeme]);
        
        // Récupère le premier résultat
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Si aucun résultat n'est trouvé, retourne null
        if ($row === false) {
            return null;
        }
        
        // Crée et retourne un objet Systeme avec les données trouvées
        return new Systeme(
            $row['Nom_du_systeme'],
            $row['date_derniere_mise_a_jour'],
            $row['image_systeme'],
            $row['Numero_de_serie'],
            $row['Fabriquant'],
            $row['Date_fabrication'],
            $row['Description'],
            $row['id_systeme']
        );
    }
}
?>