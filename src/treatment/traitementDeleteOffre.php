<?php
session_start();
require_once "../../src/repository/OffreRepository.php";

if (isset($_POST['delete_offre']) && isset($_POST['id_offre'])) {

    $id_offre = (int) $_POST['id_offre'];

    $offreRepository = new OffreRepository();
    $offreRepository->deleteOffre($id_offre);

    // Message de succès (optionnel)
    $_SESSION['success'] = "Offre supprimée avec succès.";

    // Redirection correcte
    header("Location: ../../view/emplois.php");
    exit();
} else {

    $_SESSION['error'] = "Impossible de supprimer l'offre.";
    header("Location: ../../view/emplois.php");
    exit();
}
