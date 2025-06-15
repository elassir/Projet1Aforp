<?php
// Inclut la classe Matiere pour pouvoir l'utiliser dans ce fichier
include_once 'Matiere.php';

// Classe responsable de la gestion des opérations de base de données pour les matières
class MatiereRepository {
    // Variable qui stocke la connexion à la base de données
    private $pdo;

    // Constructeur qui initialise la connexion à la base de données
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour récupérer toutes les matières de la base de données
    public function findAll() {
        // Exécute une requête pour récupérer toutes les matières
        $stmt = $this->pdo->query("SELECT * FROM matiere");
        $matieres = [];
        
        // Pour chaque ligne de résultat, crée un objet Matiere et l'ajoute au tableau
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $matieres[] = new Matiere($row['Nom_matiere'], $row['id_matiere']);
        }
        
        // Retourne toutes les matières trouvées
        return $matieres;
    }

    // Méthode pour récupérer une matière par son ID
    public function findById($id_matiere) {
        // Prépare et exécute une requête pour récupérer une matière spécifique
        $stmt = $this->pdo->prepare("SELECT * FROM matiere WHERE id_matiere = ?");
        $stmt->execute([$id_matiere]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Si une matière est trouvée, crée et retourne un objet Matiere
        if ($row) {
            return new Matiere($row['Nom_matiere'], $row['id_matiere']);
        }
        
        // Si aucune matière n'est trouvée, retourne null
        return null;
    }

    // Méthode pour sauvegarder une matière dans la base de données (création ou mise à jour)
    public function save(Matiere $matiere) {
        // Si la matière a déjà un ID, c'est une mise à jour
        if ($matiere->getIdMatiere()) {
            $stmt = $this->pdo->prepare("UPDATE matiere SET Nom_matiere = ? WHERE id_matiere = ?");
            $stmt->execute([$matiere->getNomMatiere(), $matiere->getIdMatiere()]);
        } else {
            // Sinon, c'est une nouvelle entrée à insérer
            $stmt = $this->pdo->prepare("INSERT INTO matiere (Nom_matiere) VALUES (?)");
            $stmt->execute([$matiere->getNomMatiere()]);
            // Mise à jour de l'ID de la matière avec celui généré par la base de données
            $matiere->setIdMatiere($this->pdo->lastInsertId());
        }
    }

    // Méthode pour supprimer une matière par son ID
    public function delete($id_matiere) {
        $stmt = $this->pdo->prepare("DELETE FROM matiere WHERE id_matiere = ?");
        $stmt->execute([$id_matiere]);
    }
}
?>