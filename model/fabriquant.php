<?php
// Définition de la classe Fabriquant qui représente un fabricant de systèmes dans l'application
class Fabriquant {
    // Propriétés de la classe
    private $siret;    // Numéro SIRET (identifiant unique du fabricant)
    private $nom;      // Nom du fabricant
    private $tel;      // Numéro de téléphone du fabricant
    private $adresse;  // Adresse postale du fabricant

    // Constructeur pour initialiser un nouvel objet Fabriquant
    public function __construct($nom, $tel, $adresse, $siret) {
        // Initialisation des propriétés avec les valeurs fournies
        $this->nom = $nom;
        $this->tel = $tel;
        $this->siret = $siret;
        $this->adresse = $adresse;
    }

    // GETTERS - Méthodes pour récupérer les valeurs des propriétés
    
    // Récupère le SIRET du fabricant
    public function getSiret() {
        return $this->siret;
    }

    // Récupère le nom du fabricant
    public function getNom() {
        return $this->nom;
    }

    // Récupère le numéro de téléphone du fabricant
    public function getTel() {
        return $this->tel;
    }

    // Récupère l'adresse du fabricant
    public function getAdresse() {
        return $this->adresse;
    }

    // SETTERS - Méthodes pour modifier les valeurs des propriétés
    
    // Modifie le nom du fabricant
    public function setNom($nom) {
        $this->nom = $nom;
    }

    // Modifie le numéro de téléphone du fabricant
    public function setTel($tel) {
        $this->tel = $tel;
    }

    // Modifie l'adresse du fabricant
    public function setAdresse($adresse) {
        $this->adresse = $adresse;
    }

    // Modifie le SIRET du fabricant
    public function setSiret($siret) {
        $this->siret = $siret;
    }
}
?>