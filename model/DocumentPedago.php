<?php

class DocumentPedago {
    private $id_pedagogique;
    private $Systeme_concerne;
    private $Date_Document;
    private $Type_document;
    private $Doc_file;
    private $id_matiere;

    public function __construct($Nom_matiere, $Systeme_concerne, $Date_Document, $Type_document, $Doc_file, $id_pedagogique = null, $id_matiere = null) {
        $this->id_pedagogique = $id_pedagogique;
        $this->Systeme_concerne = $Systeme_concerne;
        $this->Date_Document = $Date_Document;
        $this->Type_document = $Type_document;
        $this->Doc_file = $Doc_file;
        $this->id_matiere = $id_matiere;
    }

    // Getters
    public function getIdPedagogique() {
        return $this->id_pedagogique;
    }

    public function getSystemeConcerne() {
        return $this->Systeme_concerne;
    }

    public function getDateDocument() {
        return $this->Date_Document;
    }

    public function getTypeDocument() {
        return $this->Type_document;
    }

    public function getDocFile() {
        return $this->Doc_file;
    }

    public function getIdMatiere() {
        return $this->id_matiere;
    }

    // Setters
    public function setIdPedagogique($id_pedagogique) {
        $this->id_pedagogique = $id_pedagogique;
    }

    public function setSystemeConcerne($Systeme_concerne) {
        $this->Systeme_concerne = $Systeme_concerne;
    }

    public function setDateDocument($Date_Document) {
        $this->Date_Document = $Date_Document;
    }

    public function setTypeDocument($Type_document) {
        $this->Type_document = $Type_document;
    }

    public function setDocFile($Doc_file) {
        $this->Doc_file = $Doc_file;
    }

    public function setIdMatiere($id_matiere) {
        $this->id_matiere = $id_matiere;
    }
}
?>