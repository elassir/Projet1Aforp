<?php
include_once '../controlleur/connexion.php';
include_once '../model/DocumentPedago.php';
include_once '../model/DocumentPedagoRepository.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_matiere = $_POST['id_matiere'];
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
        null, // Nom_matiere n'est plus utilisé
        $Systeme_concerne,
        $Date_Document,
        $Type_document,
        $doc_file_path,
        null, // id_pedagogique
        $id_matiere
    );

    $documentPedagoRepository = new DocumentPedagoRepository($pdo);
    $documentPedagoRepository->save($documentPedago);

    echo "<p>Le document pédagogique a été ajouté avec succès. Vous allez être redirigé dans quelques secondes...</p>";
    echo "<script>
            setTimeout(function() {
                window.location.href = '../vue/gestion_docPedago.php';
            }, 3000);
          </script>";
}
?>