<?php

class DocumentTechniqueRepository{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;

    }
    public function save (DocumentTechnique $documentTechnique){
        try {
            $this->pdo->beginTransaction();
            $Systeme_concerne = $documentTechnique->getSysteme_concerne();

        }
    }
}