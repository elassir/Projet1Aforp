<?php
class Systeme {
    public $Nom_du_systeme;
    public $id_systeme;
    public $date_derniere_mise_a_jour;
    public $image_systeme;
    public $Numero_de_serie;
    public $Fabriquant;
    public $Date_fabrication;
    public $Description;

    public function __construct($Nom_du_systeme, $date_derniere_mise_a_jour, $image_systeme, $Numero_de_serie, $Fabriquant, $Date_fabrication, $Description) {
        $this->Nom_du_systeme = $Nom_du_systeme;
        //$this->id_systeme = $id_systeme;
        $this->date_derniere_mise_a_jour = $date_derniere_mise_a_jour;
        $this->image_systeme = $image_systeme;
        $this->Numero_de_serie = $Numero_de_serie;
        $this->Fabriquant = $Fabriquant;
        $this->Date_fabrication = $Date_fabrication;
        $this->Description = $Description;
    }

    // Getters
    public function getIdSysteme(){
        return $this->id_systeme;
    }

    public function getNomDuSysteme()  {
        return $this->Nom_du_systeme;
    }

    public function getDateDerniereMiseAJour(){
        return $this->date_derniere_mise_a_jour;
    }

    public function getImageSysteme() {
        return $this->image_systeme;
    }

    public function getNumeroDeSerie() {
        return $this->Numero_de_serie;
    }

    public function getFabriquant(){
        return $this->Fabriquant;
    }

    public function getDateFabrication() {
        return $this->Date_fabrication;
    }

    public function getDescription() {
        return $this->Description;
    }

    // Setters
    public function setNomDuSysteme( $Nom_du_systeme){
        $this->nom_du_systeme = $Nom_du_systeme;
    }

    public function setDateDerniereMiseAJour($date_derniere_mise_a_jour) {
        $this->date_derniere_mise_a_jour = $date_derniere_mise_a_jour;
    }

    public function setImageSysteme( $image_systeme) {
        $this->image_systeme = $image_systeme;
    }

    public function setNumeroDeSerie($Numero_de_serie) {
        $this->numero_de_serie = $Numero_de_serie;
    }

    public function setFabriquant($Fabriquant){
        $this->fabriquant = $Fabriquant;
    }

    public function setDateFabrication($Date_fabrication){
        $this->date_fabrication = $Date_fabrication;
    }

    public function setDescription( $Description){
        $this->description = $Description;
    }

    public function setIdSysteme(?int $id_systeme) {
        $this->id_systeme = $id_systeme;
    }
    public function getVersions(){
        return "1.1";
    }
}
?>