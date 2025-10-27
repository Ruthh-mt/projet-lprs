<?php
require_once '../../src/bdd/config.php' ;

$bdd = new Config() ;
$pdo = $bdd-> connexion() ;


$id = (int) $_POST['id_offre'] ;
// Récupération de l'offre
$stmt = $pdo->prepare("SELECT * FROM offre  WHERE id_offre = ?");
$stmt->execute([$id]);
$offre = $stmt->fetch(PDO::FETCH_ASSOC);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id_offre'] ;
    $titre_poste = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $mission = trim($_POST['mission'] ?? '');
    $salaire = trim($_POST['salaire'] ?? '');
    $type_contrat = trim($_POST['type_contrat'] ?? '');
    $desciption = trim($_POST['description'] ?? '');
    $etat = trim($_POST['etat'] ?? '');
    $ref_fiche = trim($_POST['ref_fiche'] ?? '');

    $sql = "UPDATE offre
                SET  titre=?,description=?, mission=?,salaire=? , type =?,etat=? ,ref_fiche=? 
                WHERE id_offre=? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([ $titre_poste,$desciption,$mission,$salaire,$type_contrat,$etat,$ref_fiche, $id]);

    header("Location: ../../view/emplois.php?");
    exit;
}
?>