<?php
include_once '../controlleur/connexion.php';
include_once '../model/DocumentPedago.php';
include_once '../model/DocumentPedagoRepository.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Nom_matiere = $_POST['Nom_matiere'];
    $Systeme_concerne = $_POST['Systeme_concerne'];
    $Date_Document = $_POST['Date_Document'];
    $Type_document = $_POST['Type_document'];
    $Doc_file = $_FILES['Doc_file'];
    $doc_file_path = null;

    if ($Doc_file && $Doc_file['error'] == 0) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($Doc_file['name']);
        if (move_uploaded_file($Doc_file['tmp_name'], $target_file)) {
            $doc_file_path = basename($Doc_file['name']);
        }
    }

    $documentPedago = new DocumentPedago(
        $Nom_matiere,
        $Systeme_concerne,
        $Date_Document,
        $Type_document,
        $doc_file_path
    );

    $documentPedagoRepository = new DocumentPedagoRepository($pdo);
    $documentPedagoRepository->save($documentPedago);

    echo "Le document pédagogique a été ajouté avec succès. ID : " . $documentPedago->getIdPedagogique();
}
?>