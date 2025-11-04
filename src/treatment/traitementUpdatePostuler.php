<?php
require_once '../../src/bdd/config.php' ;
require_once __DIR__ . '/../repository/PostulerRepository.php';

$bdd = new Config() ;
$pdo = $bdd-> connexion() ;
$id = (int) $_POST['id_offre'] ;
// Récupération de l'offre
$stmt = $pdo->prepare("SELECT * FROM postuler  WHERE ref_offre = ?");
$stmt->execute([$id]);
$offre = $stmt->fetch(PDO::FETCH_ASSOC);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['motivation'])) {
        $ref_offre = $_POST['ref_offre'];
        $motivation = $_POST['motivation'];

    }
    header("Location: ../../view/profil.php?");
    exit;
}
?>