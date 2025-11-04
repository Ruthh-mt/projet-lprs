<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../../src/bdd/config.php';
$bdd = new Config() ;
$pdo = $bdd ->connexion() ;

if (isset($_POST['delete_candidature'])) {
    $ref_offre = (int)$_POST['id_offre'] ?? 0;
    $ref_user = $_SESSION['utilisateur']['id_user'] ?? 0;


    // Suppression de l’offre
    $stmt = $pdo->prepare("DELETE FROM postuler WHERE ref_user = ? and ref_offre = ? ;");
    $ok = $stmt->execute([$ref_user,$ref_offre]);
    if ($ok) {
        echo "<script>alert('Votre candidature a été supprimé !'); window.location.href='../../candidatures.php';</script>";
        exit;
    } else {
        echo "<script>alert('Erreur lors de la suppression de la candidature.'); window.history.back();</script>";
        exit;

    }
}
else {
    echo "<script>alert('Problème candidature.'); window.history.back();</script>";
    exit;
}
?>
