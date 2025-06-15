<?php
// Définition de la classe Systeme qui représente un système technique dans l'application
class Systeme {
    // Propriétés de la classe (caractéristiques d'un système)
    public $Nom_du_systeme;      // Nom du système
    public $id_systeme;          // Identifiant unique du système dans la base de données
    public $date_derniere_mise_a_jour;  // Date de la dernière mise à jour du système
    public $image_systeme;       // Chemin vers l'image du système
    public $Numero_de_serie;     // Numéro de série du système
    public $Fabriquant;          // SIRET du fabricant du système
    public $Date_fabrication;    // Date de fabrication du système
    public $Description;         // Description détaillée du système

    // Constructeur pour initialiser un nouvel objet Systeme
    public function __construct($Nom_du_systeme, $date_derniere_mise_a_jour, $image_systeme, $Numero_de_serie, $Fabriquant, $Date_fabrication, $Description, $id_systeme = null) {
        // Initialisation des propriétés avec les valeurs fournies
        $this->Nom_du_systeme = $Nom_du_systeme;
        $this->id_systeme = $id_systeme;
        $this->date_derniere_mise_a_jour = $date_derniere_mise_a_jour;
        $this->image_systeme = $image_systeme;
        $this->Numero_de_serie = $Numero_de_serie;
        $this->Fabriquant = $Fabriquant;
        $this->Date_fabrication = $Date_fabrication;
        $this->Description = $Description;
    }

    // GETTERS - Méthodes pour récupérer les valeurs des propriétés
    
    // Récupère l'ID du système
    public function getIdSysteme(){
        return $this->id_systeme;
    }

    // Récupère le nom du système
    public function getNomDuSysteme()  {
        return $this->Nom_du_systeme;
    }

    // Récupère la date de dernière mise à jour
    public function getDateDerniereMiseAJour(){
        return $this->date_derniere_mise_a_jour;
    }

    // Récupère le chemin de l'image
    public function getImageSysteme() {
        return $this->image_systeme;
    }

    // Récupère le numéro de série
    public function getNumeroDeSerie() {
        return $this->Numero_de_serie;
    }

    // Récupère le SIRET du fabricant
    public function getFabriquant(){
        return $this->Fabriquant;
    }

    // Récupère la date de fabrication
    public function getDateFabrication() {
        return $this->Date_fabrication;
    }

    // Récupère la description
    public function getDescription() {
        return $this->Description;
    }

    // SETTERS - Méthodes pour modifier les valeurs des propriétés
    
    // Modifie le nom du système
    public function setNomDuSysteme( $Nom_du_systeme){
        $this->Nom_du_systeme = $Nom_du_systeme;
    }

    // Modifie la date de dernière mise à jour
    public function setDateDerniereMiseAJour($date_derniere_mise_a_jour) {
        $this->date_derniere_mise_a_jour = $date_derniere_mise_a_jour;
    }

    // Modifie le chemin de l'image
    public function setImageSysteme( $image_systeme) {
        $this->image_systeme = $image_systeme;
    }

    // Modifie le numéro de série
    public function setNumeroDeSerie($Numero_de_serie) {
        $this->Numero_de_serie = $Numero_de_serie;
    }

    // Modifie le SIRET du fabricant
    public function setFabriquant($Fabriquant){
        $this->Fabriquant = $Fabriquant;
    }

    // Modifie la date de fabrication
    public function setDateFabrication($Date_fabrication){
        $this->Date_fabrication = $Date_fabrication;
    }

    // Modifie la description
    public function setDescription( $Description){
        $this->Description = $Description;
    }

    // Modifie l'ID du système (null si c'est un nouveau système)
    public function setIdSysteme(?int $id_systeme) {
        $this->id_systeme = $id_systeme;
    }
    
    // Récupère la version du système (actuellement fixée à "1.1")
    public function getVersions(){
        return "1.1";
    }
}
?>