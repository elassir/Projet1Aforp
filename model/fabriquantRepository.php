<?php
// Inclut la classe Fabriquant pour pouvoir l'utiliser dans ce fichier
include_once 'fabriquant.php';

// Classe responsable de la gestion des opérations de base de données pour les fabricants
class FabriquantRepository {
    // Variable qui stocke la connexion à la base de données
    private $pdo;

    // Constructeur qui initialise la connexion à la base de données
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Méthode pour sauvegarder un nouveau fabricant dans la base de données
    public function save(Fabriquant $fabriquant) {
        try {
            // Démarre une transaction pour garantir l'intégrité des données
            $this->pdo->beginTransaction();
            
            // Prépare et exécute la requête d'insertion d'un nouveau fabricant
            $stmt = $this->pdo->prepare("INSERT INTO fabriquant (Nom, Tel, Adresse, Siret) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $fabriquant->getNom(),
                $fabriquant->getTel(),
                $fabriquant->getAdresse(),
                $fabriquant->getSiret()
            ]);
            
            // Valide la transaction si tout s'est bien passé
            $this->pdo->commit();
        } catch (Exception $e) {
            // Annule la transaction en cas d'erreur
            $this->pdo->rollBack();
            // Remonte l'exception pour qu'elle puisse être gérée plus haut
            throw $e;
        }
    }
    
    // Méthode pour mettre à jour un fabricant existant dans la base de données
    public function update(Fabriquant $fabriquant) {
        try {
            // Démarre une transaction pour garantir l'intégrité des données
            $this->pdo->beginTransaction();
            
            // Prépare et exécute la requête de mise à jour d'un fabricant existant
            $stmt = $this->pdo->prepare("UPDATE fabriquant SET Nom = ?, Tel = ?, Adresse = ? WHERE Siret = ?");
            $stmt->execute([
                $fabriquant->getNom(),
                $fabriquant->getTel(),
                $fabriquant->getAdresse(),
                $fabriquant->getSiret()
            ]);
            
            // Valide la transaction si tout s'est bien passé
            $this->pdo->commit();
        } catch (Exception $e) {
            // Annule la transaction en cas d'erreur
            $this->pdo->rollBack();
            // Remonte l'exception pour qu'elle puisse être gérée plus haut
            throw $e;
        }
    }

    // Méthode pour récupérer tous les fabricants de la base de données
    public function findAll() {
        // Exécute une requête pour récupérer tous les fabricants
        $stmt = $this->pdo->query("SELECT * FROM fabriquant");
        $fabriquants = [];
        
        // Pour chaque ligne de résultat, crée un objet Fabriquant et l'ajoute au tableau
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $fabriquants[] = new Fabriquant(
                $row['Nom'],
                $row['Tel'],
                $row['Adresse'],
                $row['Siret']
            );
        }
        
        // Retourne tous les fabricants trouvés
        return $fabriquants;
    }

    // Méthode pour récupérer un fabricant par son SIRET
    public function findBySiret($Siret) {
        // Prépare et exécute une requête pour récupérer un fabricant spécifique
        $stmt = $this->pdo->prepare("SELECT * FROM fabriquant WHERE Siret = ?");
        $stmt->execute([$Siret]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Si aucun fabricant n'est trouvé, retourne null
        if ($row == null) {
            return null;
        }
        
        // Sinon, crée et retourne un objet Fabriquant avec les données récupérées
        return new Fabriquant(
            $row['Nom'],
            $row['Tel'],
            $row['Adresse'],
            $row['Siret']
        );
    }
}
?>