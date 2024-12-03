<?php
include_once '../model/systeme.php';

class SystemeRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    

    public function save(Systeme $systeme) {
        try {
            $this->pdo->beginTransaction();
            $Fabriquant = $systeme->getFabriquant();
            $stmtCheck = $this->pdo->prepare("SELECT COUNT(*) FROM fabriquant WHERE Siret = ?");
            $stmtCheck->execute([$Fabriquant]);

            if ($stmtCheck->fetchColumn() == 0) {
                throw new Exception("Le fabriquant spécifié n'existe pas dans la table 'fabriquant'.");
            }

            if ($systeme->getIdSysteme() == null) {
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
                $systeme->setIdSysteme($this->pdo->lastInsertId());
            } else {
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

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM systeme");
        $systemes = [];
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
        return $systemes;
    }
}
?>