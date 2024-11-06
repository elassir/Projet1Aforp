<?php
include_once 'fabriquant.php';

class FabriquantRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function save(Fabriquant $fabriquant) {
        try {
            $this->pdo->beginTransaction();
            if ($fabriquant->getSiret() == null) {
                $stmt = $this->pdo->prepare("INSERT INTO fabriquant (Nom, Tel, Adresse, Siret) VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    $fabriquant->getNom(),
                    $fabriquant->getTel(),
                    $fabriquant->getAdresse(),
                    $fabriquant->getSiret()
                ]);
                $fabriquant->setSiret($this->pdo->lastInsertId());
            } else {
                $stmt = $this->pdo->prepare("UPDATE fabriquant SET Nom = ?, Tel = ?, Adresse = ? WHERE Siret = ?");
                $stmt->execute([
                    $fabriquant->getNom(),
                    $fabriquant->getTel(),
                    $fabriquant->getAdresse(),
                    $fabriquant->getSiret()
                ]);
            }
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM fabriquant");
        $fabriquants = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $fabriquants[] = new Fabriquant(
                $row['Nom'],
                $row['Tel'],
                $row['Adresse'],
                $row['Siret']
            );
        }
        return $fabriquants;
    }

    public function findBySiret($Siret) {
        $stmt = $this->pdo->prepare("SELECT * FROM fabriquant WHERE Siret = ?");
        $stmt->execute([$Siret]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row == null) {
            return null;
        }
        return new Fabriquant(
            $row['Nom'],
            $row['Tel'],
            $row['Adresse'],
            $row['Siret']
        );
    }
}
?>