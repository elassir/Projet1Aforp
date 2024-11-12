<?php

class DocumentPedago {
    private $id_pedagogique;
    private $Nom_matiere;
    private $Systeme_concerne;
    private $Date_Document;
    private $Type_document;
    private $Doc_file;

    public function __construct($Nom_matiere, $Systeme_concerne, $Date_Document, $Type_document, $Doc_file, $id_pedagogique = null) {
        $this->id_pedagogique = $id_pedagogique;
        $this->Nom_matiere = $Nom_matiere;
        $this->Systeme_concerne = $Systeme_concerne;
        $this->Date_Document = $Date_Document;
        $this->Type_document = $Type_document;
        $this->Doc_file = $Doc_file;
    }

    // Getters
    public function getIdPedagogique() {
        return $this->id_pedagogique;
    }

    public function getNomMatiere() {
        return $this->Nom_matiere;
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

    // Setters
    public function setIdPedagogique($id_pedagogique) {
        $this->id_pedagogique = $id_pedagogique;
    }

    public function setNomMatiere($Nom_matiere) {
        $this->Nom_matiere = $Nom_matiere;
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
}
?>