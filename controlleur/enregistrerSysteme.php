<?php 
include_once '../controlleur/connexion.php';
include_once '../model/systeme.php';
include_once '../model/systemeRepository.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Nom = $_POST['Nom'];
    $date_derniere_mise_a_jour = $_POST['date_mise_a_jour'];
    $Numero_de_serie = $_POST['Numero_de_serie'];
    $Fabriquant = $_POST['Fabriquant'];
    $Date_fabrication = $_POST['Date_fabrication'];
    $Description = $_POST['Description'];

    $image_systeme = $_FILES['image_systeme'];
    $image_path = null;
    if ($image_systeme && $image_systeme['error'] == 0) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($image_systeme['name']);
        if (move_uploaded_file($image_systeme['tmp_name'], $target_file)) {
            $image_path = basename($image_systeme['name']);
        }
    }

    $systeme = new Systeme(
        $Nom, 
        $date_derniere_mise_a_jour,  
        $image_path,
        $Numero_de_serie,
        $Fabriquant, 
        $Date_fabrication, 
        $Description
    );

    $systemeRepository = new SystemeRepository($pdo);
    $systemeRepository->save($systeme);

    echo "Le système a été ajouté avec succès. ID : " . $systeme->getIdSysteme();
}
?>