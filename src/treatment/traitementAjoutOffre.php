<?php
require_once("../../src/bdd/config.php");
require_once "../modele/OffreModel.php";
require_once "../../src/repository/OffreRepository.php";

$offreRepository = new OffreRepository();

try {
    $pdo = (new Config())->connexion();

    // Vérification des champs obligatoires
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
    $salaire = (float) $_POST['salaire'];
    $id_entreprise = (int) $_POST['entreprise'];
    $etat = "En attente"; // valeur par défaut

    // Vérification du salaire
    if ($salaire < 0) {
        echo "<script>alert('Le salaire doit être un nombre positif.'); window.history.back();</script>";
        exit;
    }

    // Requête d’insertion
  $offre = new OffreModel(array(
      'titre' => $titre,
      'description' => $description,
      'mission' => $mission,
      'type' => $type,
      'etat' => $etat,
      'salaire' => $salaire,
      'ref_fiche' => $id_entreprise
  )) ;

   $ok = $offreRepository ->createOffre($offre);
   if($ok){
       // Message de succès et redirection
       echo "<script>alert('Offre créée avec succès !'); window.location.href='../../view/emplois.php';</script>";
       exit;
   }

} catch (PDOException $e) {
    echo "<script>alert('Erreur lors de la création de l’offre : " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    exit;
}
