<?php
use bdd\Bdd;
require '../../src/bdd/config.php';
$bdd = new Config() ;
$pdo = $bdd ->connexion() ;

if (isset($_POST['delete_candidature'])) {
    $idOffre = intval($_POST['id_offre']);
    $idUser = intval($_SESSION['utilisateur']['id_user']);

    if ($idOffre > 0) {
        try {
            // Suppression de l’offre
            $stmt = $pdo->prepare("DELETE FROM postuler WHERE ref_offre = ? and ref_user = ?");
            $stmt->execute([$idOffre, $idUser]);
        } catch (Exception $e) {
            echo "Erreur SQL : " . $e->getMessage();
            exit;
        }
    } else {
        echo "ID d'offre invalide.";
        exit;
    }
}
header("Location: ../view/profil.php.php");
exit;
