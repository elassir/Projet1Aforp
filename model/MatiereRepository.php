<?php
include_once 'Matiere.php';

class MatiereRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM matiere");
        $matieres = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $matieres[] = new Matiere($row['Nom_matiere'], $row['id_matiere']);
        }
        return $matieres;
    }

    public function findById($id_matiere) {
        $stmt = $this->pdo->prepare("SELECT * FROM matiere WHERE id_matiere = ?");
        $stmt->execute([$id_matiere]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Matiere($row['Nom_matiere'], $row['id_matiere']);
        }
        return null;
    }

    public function save(Matiere $matiere) {
        if ($matiere->getIdMatiere()) {
            $stmt = $this->pdo->prepare("UPDATE matiere SET Nom_matiere = ? WHERE id_matiere = ?");
            $stmt->execute([$matiere->getNomMatiere(), $matiere->getIdMatiere()]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO matiere (Nom_matiere) VALUES (?)");
            $stmt->execute([$matiere->getNomMatiere()]);
            $matiere->setIdMatiere($this->pdo->lastInsertId());
        }
    }

    public function delete($id_matiere) {
        $stmt = $this->pdo->prepare("DELETE FROM matiere WHERE id_matiere = ?");
        $stmt->execute([$id_matiere]);
    }
}
?>