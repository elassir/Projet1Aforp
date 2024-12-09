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
                $stmt = $this->pdo->prepare("INSERT INTO document_pedagogique (Systeme_concerne, Date_Document, Type_document, Doc_file, id_matiere) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $documentPedago->getSystemeConcerne(),
                    $documentPedago->getDateDocument(),
                    $documentPedago->getTypeDocument(),
                    $documentPedago->getDocFile(),
                    $documentPedago->getIdMatiere()
                ]);
                $documentPedago->setIdPedagogique($this->pdo->lastInsertId());
            } else {
                $stmt = $this->pdo->prepare("UPDATE document_pedagogique SET Systeme_concerne = ?, Date_Document = ?, Type_document = ?, Doc_file = ?, id_matiere = ? WHERE id_pedagogique = ?");
                $stmt->execute([
                    $documentPedago->getSystemeConcerne(),
                    $documentPedago->getDateDocument(),
                    $documentPedago->getTypeDocument(),
                    $documentPedago->getDocFile(),
                    $documentPedago->getIdMatiere(),
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
                null, // Nom_matiere n'est plus utilisé
                $row['Systeme_concerne'],
                $row['Date_Document'],
                $row['Type_document'],
                $row['Doc_file'],
                $row['id_pedagogique'],
                $row['id_matiere']
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
            null, // Nom_matiere n'est plus utilisé
            $row['Systeme_concerne'],
            $row['Date_Document'],
            $row['Type_document'],
            $row['Doc_file'],
            $row['id_pedagogique'],
            $row['id_matiere']
        );
    }

    public function findBySysteme($Systeme_concerne) {
        $stmt = $this->pdo->prepare("SELECT * FROM document_pedagogique WHERE Systeme_concerne = ?");
        $stmt->execute([$Systeme_concerne]);
        $documents = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $documents[] = new DocumentPedago(
                null, // Nom_matiere n'est plus utilisé
                $row['Systeme_concerne'],
                $row['Date_Document'],
                $row['Type_document'],
                $row['Doc_file'],
                $row['id_pedagogique'],
                $row['id_matiere']
            );
        }
        return $documents;
    }

    public function findByApprenti($id_apprenti) {
        $stmt = $this->pdo->prepare("
            SELECT dp.* 
            FROM document_pedagogique dp
            JOIN apprenti_devoir ad ON dp.id_pedagogique = ad.Devoir
            WHERE ad.Apprenti = ?
        ");
        $stmt->execute([$id_apprenti]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'DocumentPedago');
    }
}
?>