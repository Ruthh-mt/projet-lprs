<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../modele/ModeleFicheEntreprise.php';
require_once __DIR__ . '/../bdd/config.php';
require_once __DIR__ . '/../repository/FicheEntrepriseRepository.php';

if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'Gestionnaire') {
    $_SESSION['error'] = "Accès refusé. Vous devez être connecté en tant que gestionnaire.";
    header('Location: ../../view/connexion.php');
    exit();
}

if (!isset($_POST['id_fiche_entreprise']) || !is_numeric($_POST['id_fiche_entreprise'])) {
    $_SESSION['error'] = "ID de fiche entreprise invalide.";
    header('Location: ../../view/crudEntreprise/entrepriseRead.php');
    exit();
}

$id_fiche_entreprise = (int)$_POST['id_fiche_entreprise'];

try {
    $ficheEntrepriseRepository = new FicheEntrepriseRepository();
    
    $fiche = $ficheEntrepriseRepository->getFicheEntrepriseById($id_fiche_entreprise);
    
    if (!$fiche) {
        throw new Exception("La fiche entreprise spécifiée n'existe pas.");
    }
        
    $result = $ficheEntrepriseRepository->deleteFicheEntreprise($id_fiche_entreprise);
    
    if ($result) {
        $_SESSION['success'] = "La fiche entreprise a été supprimée avec succès.";
    } else {
        throw new Exception("Une erreur est survenue lors de la suppression de la fiche entreprise.");
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur lors de la suppression de la fiche entreprise : " . $e->getMessage();
}

header('Location: ../../view/crudEntreprise/entrepriseRead.php');
exit();
