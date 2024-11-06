<?php
class Fabriquant {
    private $siret;
    private $nom;
    private $tel;
    private $adresse;

    public function __construct($nom, $tel, $adresse, $siret = null) {
        $this->nom = $nom;
        $this->tel = $tel;
        $this->siret = $siret;
        $this->adresse = $adresse;
    }

    public function getSiret() {
        return $this->siret;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getTel() {
        return $this->tel;
    }

    public function getAdresse() {
        return $this->adresse;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setTel($tel) {
        $this->tel = $tel;
    }

    public function setAdresse($adresse) {
        $this->adresse = $adresse;
    }

    public function setSiret($siret) {
        $this->siret = $siret;
    }
}
?>