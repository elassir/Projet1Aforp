<?php

class DocumentTechnique {
    private $id_technique;
    private $Nom_doc_tech;

    private $Date;
    private $Categorie;
    private $Systeme_concerne;
    private $Doc_file;
    private $Version;

    public function __construct($Nom_doc_tech, $Date, $Categorie, $Systeme_concerne, $Doc_file,$Version, $id_technique = null) {
        $this->Nom_doc_tech = $Nom_doc_tech;
        $this->id_technique = $id_technique;
        $this->Date = $Date;
        $this->Categorie = $Categorie;
        $this->Systeme_concerne = $Systeme_concerne;
        $this->Doc_file = $Doc_file;
        $this->Version = $Version;
    }

    // Getters
    public function getid_Technique(){
        return $this->id_technique;
    }

    public function getNom_doc_tech()  {
        return $this->Nom_doc_tech;
    }
    public function getDate() {
        return $this->Date;
    }
    public function getCategorie() {
        return $this->Categorie;
    }
    public function getSysteme_concerne() {
        return $this->Systeme_concerne;
    }
    public function getDocFile() {
        return $this->Doc_file;
    }
    public function getVersion() {
        return $this->Version;
    }

    // Setters
    public function setid_Technique($id_technique) {
        $this->id_technique = $id_technique;
    }
    public function setNom_doc_tech($Nom_doc_tech) {
        $this->Nom_doc_tech = $Nom_doc_tech;
    }
    public function setDate($Date)
     {
        $this->Date = $Date;
    }
    public function setCategorie($Categorie) {
        $this->Categorie = $Categorie;
    }
    public function setSysteme_concerne($Systeme_concerne) {
        $this->Systeme_concerne = $Systeme_concerne;
    }
    public function setDoc_file($Doc_file) {
        $this->Doc_file = $Doc_file;
    }
    public function setVersion($Version) {
        $this->Version = $Version;
    }



}