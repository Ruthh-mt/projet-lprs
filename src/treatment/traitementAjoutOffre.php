<?php
session_start();
require_once("../../src/bdd/config.php");
require_once "../modele/ModeleOffre.php";
require_once "../../src/repository/OffreRepository.php";
$offreRepository = new OffreRepository();
require_once("../../src/repository/PartenaireRepository.php");
$partenaire_rep = new PartenaireRepository();
$alumni_rep = new PartenaireRepository();
$id_user = $_SESSION['utilisateur']['id_user'] ;
$getFichePartenaire = $partenaire_rep ->getFicheByPartenaire($id_user);
$getFicheAlumni= $partenaire_rep ->getFicheByPartenaire($id_user);
$refFichePartenaire =  $getFichePartenaire['id_fiche_entreprise'] ;


    // Vérification des champs obligatoires
    if (
        empty($_POST['titre_poste']) ||
        empty($_POST['desc_contrat']) ||
        empty($_POST['mission']) ||
        empty($_POST['type_contrat']) ||
        empty($_POST['salaire'])
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
    $etat = "En attente"; // valeur par défaut

    // Vérification du salaire
    if ($salaire < 0) {
        echo "<script>alert('Le salaire doit être un nombre positif.'); window.history.back();</script>";
        exit;
    }

    // Requête d’insertion
    $offre = new ModeleOffre(array(
        'titreOffre' => $titre,
        'description' => $description,
        'mission' => $mission,
        'type' => $type,
        'etat' => $etat,
        'salaire' => $salaire,
        'refFiche' => $ref_fiche
    ));

    $ok = $offreRepository->createOffre($offre);
    if ($ok) {
        // Message de succès et redirection
        echo "<script>alert('Offre créée avec succès !'); window.location.href='../../view/emplois.php';</script>";
        exit;
    }
    else {
        echo "<script>alert('Problème !'); window.location.href='../../view/emplois.php';</script>";

    }


