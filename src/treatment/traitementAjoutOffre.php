<?php
session_start();

require_once("../../src/bdd/config.php");
require_once "../modele/ModeleOffre.php";
require_once "../../src/repository/OffreRepository.php";
require_once("../../src/repository/PartenaireRepository.php");
require_once("../../src/repository/AlumniRepository.php");

$offreRepository = new OffreRepository();
$partenaireRepository = new PartenaireRepository();
$id_user = $_SESSION['utilisateur']['id_user'];
$role = $_SESSION['utilisateur']['role'];

$ref_fiche = null;





if (
    empty($_POST['titre_poste']) ||
    empty($_POST['desc_contrat']) ||
    empty($_POST['mission']) ||
    empty($_POST['type_contrat']) ||
    empty($_POST['salaire']) ||
    empty($_POST['entreprise'])
) {
    echo "<script>alert('Tous les champs sont obligatoires.'); window.history.back();</script>";
    exit;
}


// Nettoyage des données
$titre = trim($_POST['titre_poste']);
$description = trim($_POST['desc_contrat']);
$mission = trim($_POST['mission']);
$type = trim($_POST['type_contrat']);
$salaire = $_POST['salaire'];
$etat = "En attente";
$ref_fiche = $_POST['entreprise'];
if($partenaireRepository -> getFicheByPartenaire($id_user) == null){
    $partenaireRepository->affecterFichePartenaire($id_user,$ref_fiche);
}
$offre = new ModeleOffre([
    'titreOffre' => $titre,
    'description' => $description,
    'mission' => $mission,
    'typeContrat' => $type,
    'etat' => $etat,
    'salaire' => $salaire,
    'refFiche' => $ref_fiche
]);

$ok = $offreRepository->createOffre($offre);


if ($ok) {
    echo "<script>alert('Offre créée avec succès !'); window.location.href='../../view/emplois.php';</script>";
    exit;
} else {
    echo "<script>alert('Erreur lors de la création.'); window.location.href='../../view/emplois.php';</script>";
    exit;
}
?>
