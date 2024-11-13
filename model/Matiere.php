<?php
class Matiere {
    private $id_matiere;
    private $Nom_matiere;

    public function __construct($Nom_matiere, $id_matiere = null) {
        $this->id_matiere = $id_matiere;
        $this->Nom_matiere = $Nom_matiere;
    }

    public function getIdMatiere() {
        return $this->id_matiere;
    }

    public function getNomMatiere() {
        return $this->Nom_matiere;
    }

    public function setIdMatiere($id_matiere) {
        $this->id_matiere = $id_matiere;
    }

    public function setNomMatiere($Nom_matiere) {
        $this->Nom_matiere = $Nom_matiere;
    }
}
?>