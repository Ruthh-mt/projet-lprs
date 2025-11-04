<?php
use bdd\Bdd;
require '../../src/bdd/config.php';
require_once "../../src/repository/OffreRepository.php";

$bdd = new Config() ;
$pdo = $bdd ->connexion() ;
$offreRepository = new OffreRepository();
$id_offre = (int) $_POST['id_offre'];
if (isset($_POST['delete_offre'])) {
    // Suppression de l’offre
        $offreRepository->deleteOffre($id_offre);
}


