<?php
/**
 * Classe Matiere
 * 
 * Cette classe représente une matière d'enseignement dans l'application.
 * Une matière est une discipline ou un sujet enseigné, comme "Mathématiques", "Informatique", etc.
 * Chaque document pédagogique doit être associé à une matière.
 */
class Matiere {
    /**
     * Propriétés de la classe
     * 
     * Ces variables contiennent les informations sur la matière.
     * Elles sont marquées comme "private", ce qui signifie qu'elles ne sont 
     * accessibles que depuis l'intérieur de la classe, via les méthodes publiques.
     */
    private $id_matiere;   // Identifiant unique de la matière dans la base de données
    private $Nom_matiere;  // Nom de la matière (ex: "Mathématiques", "Informatique")

    /**
     * Constructeur - Méthode appelée lors de la création d'un nouvel objet Matiere
     * 
     * @param string $Nom_matiere - Le nom de la matière à créer
     * @param int|null $id_matiere - L'identifiant unique de la matière (optionnel, utilisé lors du chargement depuis la base)
     */
    public function __construct($Nom_matiere, $id_matiere = null) {
        // Initialisation des propriétés avec les valeurs fournies
        $this->id_matiere = $id_matiere;     // Stocke l'ID fourni (ou null si aucun n'est fourni)
        $this->Nom_matiere = $Nom_matiere;   // Stocke le nom de la matière
    }

    /**
     * GETTERS - Méthodes pour récupérer les valeurs des propriétés
     * Ces méthodes permettent de lire les propriétés privées depuis l'extérieur de la classe
     */
    
    /**
     * Récupère l'ID de la matière
     * @return int|null - L'identifiant unique de la matière
     */
    public function getIdMatiere() {
        return $this->id_matiere;  // Retourne l'ID stocké
    }

    /**
     * Récupère le nom de la matière
     * @return string - Le nom de la matière
     */
    public function getNomMatiere() {
        return $this->Nom_matiere;  // Retourne le nom stocké
    }

    /**
     * SETTERS - Méthodes pour modifier les valeurs des propriétés
     * Ces méthodes permettent de modifier les propriétés privées depuis l'extérieur de la classe
     */
    
    /**
     * Modifie l'ID de la matière
     * Généralement utilisé après avoir sauvegardé une nouvelle matière en base pour stocker l'ID généré
     * 
     * @param int $id_matiere - Le nouvel identifiant à attribuer
     */
    public function setIdMatiere($id_matiere) {
        $this->id_matiere = $id_matiere;  // Remplace l'ID actuel par le nouvel ID
    }

    /**
     * Modifie le nom de la matière
     * 
     * @param string $Nom_matiere - Le nouveau nom à attribuer
     */
    public function setNomMatiere($Nom_matiere) {
        $this->Nom_matiere = $Nom_matiere;  // Remplace le nom actuel par le nouveau nom
    }
}
?>