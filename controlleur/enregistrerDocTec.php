<?php

include_once '../controlleur/connexion.php';
include_once '../model/DocumentTechnique.php';
include_once '../model/DocumentTechniqueRepository.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Nom_doc_tech = $_POST['Nom_doc_tech'];
    $Date = $_POST['Date'];
    $Categorie = $_POST['Categorie'];
    $Systeme_concerne = $_POST['Systeme_concerne'];
    $Doc_file = $_FILES['Doc_file'];
    $Version = $_POST['Version'];

    $doc_file_path = null;
    if ($Doc_file && $Doc_file['error'] == 0) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($Doc_file['name']);
        if (move_uploaded_file($Doc_file['tmp_name'], $target_file)) {
            $doc_file_path = basename($Doc_file['name']);
        }
    }

    $documentTechnique = new DocumentTechnique(
        $Nom_doc_tech,
        $Date,
        $Categorie,
        $Systeme_concerne,
        $doc_file_path,
        $Version
    );

    $documentTechniqueRepository = new DocumentTechniqueRepository($pdo);
    $documentTechniqueRepository->save($documentTechnique);

    echo "Le document technique a été ajouté avec succès. ID : " . $documentTechnique->getid_Technique();
}
