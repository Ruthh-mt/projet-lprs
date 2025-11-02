<?php
session_start();
require_once("../../src/bdd/config.php");
require_once "../repository/OffreRepository.php";
require_once "../modele/Offre.php";
require_once "../repository/EvenementUserRepository.php";
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

    if ($id_entreprise === null) {
        echo "<script>alert('Aucune entreprise sélectionnée.'); window.history.back();</script>";
        exit;
    }

    $etat = "En attente";
    // Vérification du salaire
    if ($salaire < 0) {
        echo "<script>alert('Le salaire doit être un nombre positif.'); window.history.back();</script>";
        exit;
    }
    $offre = new Offre(array(
        'titre'       => $titre,
        'description' => $description,
        'mission'     => $mission,
        'salaire'     => $salaire,
        'type'        => $type,
        'etat'        => $etat,
        'refFiche'   => $id_entreprise
    ));
    var_dump($offre->getRefFiche());
    // Requête d’insertion
    $sql = $offreRepository->createOffre($offre);
    // Message de succès et redirection
    echo "<script>alert('Offre créée avec succès !'); window.location.href='../../view/emplois.php';</script>";
    exit;

} catch (PDOException $e) {
    echo "<script>alert('Erreur lors de la création de l’offre : " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    exit;
}
