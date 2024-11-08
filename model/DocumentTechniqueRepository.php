<?php
include_once 'DocumentTechnique.php';

class DocumentTechniqueRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function save(DocumentTechnique $documentTechnique) {
        try {
            $this->pdo->beginTransaction();
            if ($documentTechnique->getid_Technique() == null) {
                $stmt = $this->pdo->prepare("INSERT INTO document_technique (Nom_doc_tech, Date, Categorie, Systeme_concerne, Doc_file, Version) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $documentTechnique->getNom_doc_tech(),
                    $documentTechnique->getDate(),
                    $documentTechnique->getCategorie(),
                    $documentTechnique->getSysteme_concerne(),
                    $documentTechnique->getDocFile(),
                    $documentTechnique->getVersion()
                ]);
                $documentTechnique->setid_Technique($this->pdo->lastInsertId());
            } else {
                $stmt = $this->pdo->prepare("UPDATE document_technique SET Nom_doc_tech = ?, Date = ?, Categorie = ?, Systeme_concerne = ?, Doc_file = ?, Version = ? WHERE id_technique = ?");
                $stmt->execute([
                    $documentTechnique->getNom_doc_tech(),
                    $documentTechnique->getDate(),
                    $documentTechnique->getCategorie(),
                    $documentTechnique->getSysteme_concerne(),
                    $documentTechnique->getDocFile(),
                    $documentTechnique->getVersion(),
                    $documentTechnique->getid_Technique()
                ]);
            }
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM document_technique");
        $documents = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $documents[] = new DocumentTechnique(
                $row['Nom_doc_tech'],
                $row['Date'],
                $row['Categorie'],
                $row['Systeme_concerne'],
                $row['Doc_file'],
                $row['Version'],
                $row['id_technique']
            );
        }
        return $documents;
    }

    public function findById($id_technique) {
        $stmt = $this->pdo->prepare("SELECT * FROM document_technique WHERE id_technique = ?");
        $stmt->execute([$id_technique]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row == null) {
            return null;
        }
        return new DocumentTechnique(
            $row['Nom_doc_tech'],
            $row['Date'],
            $row['Categorie'],
            $row['Systeme_concerne'],
            $row['Doc_file'],
            $row['Version'],
            $row['id_technique']
        );
    }

    public function findBySysteme($Systeme_concerne) {
        $stmt = $this->pdo->prepare("SELECT * FROM document_technique WHERE Systeme_concerne = ?");
        $stmt->execute([$Systeme_concerne]);
        $documents = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $documents[] = new DocumentTechnique(
                $row['Nom_doc_tech'],
                $row['Date'],
                $row['Categorie'],
                $row['Systeme_concerne'],
                $row['Doc_file'],
                $row['Version'],
                $row['id_technique']
            );
        }
        return $documents;
    }
}
?>