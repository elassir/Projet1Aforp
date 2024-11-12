<?php
include_once 'DocumentPedago.php';

class DocumentPedagoRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function save(DocumentPedago $documentPedago) {
        try {
            $this->pdo->beginTransaction();
            if ($documentPedago->getIdPedagogique() == null) {
                $stmt = $this->pdo->prepare("INSERT INTO document_pedagogique (Nom_matiere, Systeme_concerne, Date_Document, Type_document, Doc_file) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $documentPedago->getNomMatiere(),
                    $documentPedago->getSystemeConcerne(),
                    $documentPedago->getDateDocument(),
                    $documentPedago->getTypeDocument(),
                    $documentPedago->getDocFile()
                ]);
                $documentPedago->setIdPedagogique($this->pdo->lastInsertId());
            } else {
                $stmt = $this->pdo->prepare("UPDATE document_pedagogique SET Nom_matiere = ?, Systeme_concerne = ?, Date_Document = ?, Type_document = ?, Doc_file = ? WHERE id_pedagogique = ?");
                $stmt->execute([
                    $documentPedago->getNomMatiere(),
                    $documentPedago->getSystemeConcerne(),
                    $documentPedago->getDateDocument(),
                    $documentPedago->getTypeDocument(),
                    $documentPedago->getDocFile(),
                    $documentPedago->getIdPedagogique()
                ]);
            }
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM document_pedagogique");
        $documents = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $documents[] = new DocumentPedago(
                $row['Nom_matiere'],
                $row['Systeme_concerne'],
                $row['Date_Document'],
                $row['Type_document'],
                $row['Doc_file'],
                $row['id_pedagogique']
            );
        }
        return $documents;
    }

    public function findById($id_pedagogique) {
        $stmt = $this->pdo->prepare("SELECT * FROM document_pedagogique WHERE id_pedagogique = ?");
        $stmt->execute([$id_pedagogique]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row == null) {
            return null;
        }
        return new DocumentPedago(
            $row['Nom_matiere'],
            $row['Systeme_concerne'],
            $row['Date_Document'],
            $row['Type_document'],
            $row['Doc_file'],
            $row['id_pedagogique']
        );
    }

    public function findBySysteme($Systeme_concerne) {
        $stmt = $this->pdo->prepare("SELECT * FROM document_pedagogique WHERE Systeme_concerne = ?");
        $stmt->execute([$Systeme_concerne]);
        $documents = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $documents[] = new DocumentPedago(
                $row['Nom_matiere'],
                $row['Systeme_concerne'],
                $row['Date_Document'],
                $row['Type_document'],
                $row['Doc_file'],
                $row['id_pedagogique']
            );
        }
        return $documents;
    }
}
?>