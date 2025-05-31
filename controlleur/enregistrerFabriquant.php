<?php 
include_once '../controlleur/connexion.php';
include_once '../model/fabriquant.php';
include_once '../model/fabriquantRepository.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Nom = $_POST['Nom'] ?? '';
    $Siret = $_POST['Siret'] ?? '';
    $Tel = $_POST['Tel'] ?? '';
    $Adresse = $_POST['Adresse'] ?? '';

    // Débogage : afficher les données reçues
    echo "<p>Données reçues :</p>";
    echo "<p>Nom: " . htmlspecialchars($Nom) . "</p>";
    echo "<p>SIRET: " . htmlspecialchars($Siret) . "</p>";
    echo "<p>Tel: " . htmlspecialchars($Tel) . "</p>";
    echo "<p>Adresse: " . htmlspecialchars($Adresse) . "</p>";

    // Validation basique du SIRET (doit être composé de 14 chiffres)
    if (!preg_match('/^[0-9]{14}$/', $Siret)) {
        echo "<p style='color: red;'>Erreur : Le SIRET doit contenir exactement 14 chiffres.</p>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = '../vue/gestion_fabricants.php';
                }, 3000);
              </script>";
        exit;
    }try {
        $fabriquantRepository = new FabriquantRepository($pdo);
        
        // Vérifier si le SIRET existe déjà
        $fabricantExistant = $fabriquantRepository->findBySiret($Siret);
        if ($fabricantExistant !== null) {
            echo "<p style='color: red;'>Erreur : Un fabricant avec ce SIRET existe déjà.</p>";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = '../vue/gestion_fabricants.php';
                    }, 3000);
                  </script>";
            exit;
        }

        $fabricant = new Fabriquant(
            $Nom,
            $Tel,
            $Adresse,
            $Siret
        );

        $fabriquantRepository->save($fabricant);

        echo "<p style='color: green;'>Le fabricant a été ajouté avec succès. Vous allez être redirigé dans quelques secondes...</p>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = '../vue/gestion_fabricants.php';
                }, 3000);
              </script>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>Erreur lors de l'ajout du fabricant : " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = '../vue/gestion_fabricants.php';
                }, 5000);
              </script>";
    }
}
?>
