<?php
use bdd\Bdd;
require 'src/bdd/config.php' ;
$bdd = new Config() ;
$pdo = $bdd ->connexion() ;



if (isset($_POST['delete_offre'])) {
    $idOffre = intval($_POST['id_offre'] ?? 0);

    if ($idOffre > 0) {
        try {
            // Suppression de l’offre
            $stmt = $pdo->prepare("DELETE FROM offre WHERE id_offre = ?");
            $stmt->execute([$idOffre]);

            // (Optionnel) supprimer les candidatures associées
            //$stmt2 = $pdo->prepare("DELETE FROM candidatures WHERE ref_offre = ?");
            //$stmt2->execute([$idOffre]);

        } catch (Exception $e) {
            // Pour test : afficher l'erreur temporairement
            echo "Erreur SQL : " . $e->getMessage();
            exit;
        }
    } else {
        echo "ID d'offre invalide.";
        exit;
    }
}

// 🔁 Redirection après suppression (chemin relatif à partir de ce script)
header("Location: ../../vue/Admin/emplois.php");
exit;
