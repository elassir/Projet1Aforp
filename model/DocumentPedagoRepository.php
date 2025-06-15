<?php
include_once 'DocumentPedago.php';

class DocumentPedagoRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }    public function save(DocumentPedago $documentPedago) {
        // Vérifier si une transaction est déjà en cours
        $transactionStarted = !$this->pdo->inTransaction();
        
        try {
            // Démarrer une transaction seulement si aucune n'est en cours
            if ($transactionStarted) {
                $this->pdo->beginTransaction();
            }
            
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
            
            // Valider la transaction seulement si nous l'avons démarrée
            if ($transactionStarted) {
                $this->pdo->commit();
            }
        } catch (Exception $e) {
            // Annuler la transaction seulement si nous l'avons démarrée
            if ($transactionStarted && $this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
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
        
        // Créer les objets DocumentPedago manuellement pour s'assurer que tous les champs sont correctement remplis
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
    
    /**
     * Obtient les apprentis qui ont soumis un devoir pour un document pédagogique spécifique
     * 
     * @param int $id_pedagogique ID du document pédagogique
     * @return array Tableau d'informations sur les apprentis
     */
    public function getApprentisForDevoir($id_pedagogique) {
        $stmt = $this->pdo->prepare("
            SELECT a.id_apprenti, a.Nom, a.Prenom, a.Mail, a.Promotion 
            FROM apprenti a
            JOIN apprenti_devoir ad ON a.id_apprenti = ad.Apprenti
            WHERE ad.Devoir = ?
            ORDER BY a.Nom, a.Prenom
        ");
        $stmt->execute([$id_pedagogique]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtient les documents pédagogiques filtrés par type (DEVOIR ou CONSIGNE)
     * 
     * @param string $type Le type de document (DEVOIR ou CONSIGNE)
     * @return array Tableau d'objets DocumentPedago
     */
    public function findByType($type) {
        $stmt = $this->pdo->prepare("SELECT * FROM document_pedagogique WHERE Type_document = ?");
        $stmt->execute([$type]);
        
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
    
    /**
     * Compte le nombre d'apprentis qui ont soumis un devoir pour un document pédagogique spécifique
     * 
     * @param int $id_pedagogique ID du document pédagogique
     * @return int Nombre d'apprentis
     */
    public function countApprentisForDevoir($id_pedagogique) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM apprenti_devoir 
            WHERE Devoir = ?
        ");
        $stmt->execute([$id_pedagogique]);
        return (int)$stmt->fetchColumn();
    }
      /**
     * Vérifie si un apprenti a déjà rendu un devoir pour une consigne spécifique
     * 
     * @param int $apprenti_id ID de l'apprenti
     * @param int $consigne_id ID de la consigne
     * @return bool True si l'apprenti a déjà rendu un devoir, false sinon
     */
    public function hasApprentiSubmittedDevoir($apprenti_id, $consigne_id) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM apprenti_devoir ad
            JOIN document_pedagogique devoir ON ad.Devoir = devoir.id_pedagogique
            JOIN document_pedagogique consigne ON devoir.Systeme_concerne = consigne.Systeme_concerne 
                                           AND devoir.id_matiere = consigne.id_matiere
            WHERE ad.Apprenti = ? 
            AND consigne.id_pedagogique = ?
            AND devoir.Type_document = 'DEVOIR'
            AND consigne.Type_document = 'CONSIGNE'
        ");
        $stmt->execute([$apprenti_id, $consigne_id]);
        return (int)$stmt->fetchColumn() > 0;
    }
    
    /**
     * Récupère le devoir soumis par un apprenti pour une consigne spécifique
     * 
     * @param int $apprenti_id ID de l'apprenti
     * @param int $consigne_id ID de la consigne
     * @return DocumentPedago|null Le document du devoir ou null si aucun devoir n'a été soumis
     */    public function getDevoirByApprentiForConsigne($apprenti_id, $consigne_id) {
        $stmt = $this->pdo->prepare("
            SELECT dp.* 
            FROM document_pedagogique dp
            JOIN apprenti_devoir ad ON dp.id_pedagogique = ad.Devoir
            WHERE ad.Apprenti = ? 
            AND dp.Systeme_concerne = (SELECT Systeme_concerne FROM document_pedagogique WHERE id_pedagogique = ?)
            AND dp.id_matiere = (SELECT id_matiere FROM document_pedagogique WHERE id_pedagogique = ?)
            AND dp.Type_document = 'DEVOIR'
        ");
        $stmt->execute([$apprenti_id, $consigne_id, $consigne_id]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
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
    
    /**
     * Compte le nombre d'apprentis ayant rendu un devoir pour une consigne spécifique
     * 
     * @param int $consigne_id ID de la consigne
     * @return int Nombre d'apprentis
     */
    public function countApprentisForConsigne($consigne_id) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(DISTINCT ad.Apprenti) 
            FROM apprenti_devoir ad
            JOIN document_pedagogique devoir ON ad.Devoir = devoir.id_pedagogique
            WHERE devoir.Systeme_concerne = (SELECT Systeme_concerne FROM document_pedagogique WHERE id_pedagogique = ?)
            AND devoir.id_matiere = (SELECT id_matiere FROM document_pedagogique WHERE id_pedagogique = ?)
            AND devoir.Type_document = 'DEVOIR'
        ");
        $stmt->execute([$consigne_id, $consigne_id]);
        return (int)$stmt->fetchColumn();
    }
    
    /**
     * Récupère la liste des apprentis ayant rendu un devoir pour une consigne spécifique
     * 
     * @param int $consigne_id ID de la consigne
     * @return array Tableau d'informations sur les apprentis
     */
    public function getApprentisForConsigne($consigne_id) {
        $stmt = $this->pdo->prepare("
            SELECT a.id_apprenti, a.Nom, a.Prenom, a.Mail, a.Promotion,
                   dp.id_pedagogique as devoir_id, dp.Doc_file
            FROM apprenti a
            JOIN apprenti_devoir ad ON a.id_apprenti = ad.Apprenti
            JOIN document_pedagogique dp ON ad.Devoir = dp.id_pedagogique
            WHERE dp.Systeme_concerne = (SELECT Systeme_concerne FROM document_pedagogique WHERE id_pedagogique = ?)
            AND dp.id_matiere = (SELECT id_matiere FROM document_pedagogique WHERE id_pedagogique = ?)
            AND dp.Type_document = 'DEVOIR'
            ORDER BY a.Nom, a.Prenom
        ");
        $stmt->execute([$consigne_id, $consigne_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>