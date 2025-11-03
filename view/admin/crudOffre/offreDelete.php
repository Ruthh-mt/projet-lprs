<?php
use bdd\Bdd;
require '../../src/bdd/config.php';
$bdd = new Config() ;
$pdo = $bdd ->connexion() ;

if (isset($_POST['delete_offre'])) {
    $idOffre = intval($_POST['id_offre'] ?? 0);

    if ($idOffre > 0) {
        try {
            // Suppression de l’offre
            $stmt = $pdo->prepare("DELETE FROM offre WHERE id_offre = ?");
            $stmt->execute([$idOffre]);

        } catch (Exception $e) {

            echo "Erreur SQL : " . $e->getMessage();
            exit;
        }
    } else {
        echo "ID d'offre invalide.";
        exit;
    }
}


header("Location: ../view/emplois.php");
exit;
