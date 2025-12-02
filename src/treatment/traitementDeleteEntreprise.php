<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure les fichiers nécessaires
require_once __DIR__ . '/../modele/ModeleFicheEntreprise.php';
require_once __DIR__ . '/../bdd/config.php';
require_once __DIR__ . '/../repository/FicheEntrepriseRepository.php';

// Vérifier si l'utilisateur est connecté et est un gestionnaire
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'Gestionnaire') {
    $_SESSION['error'] = "Accès refusé. Vous devez être connecté en tant que gestionnaire.";
    header('Location: ../../view/connexion.php');
    exit();
}

// Vérifier si l'ID de la fiche entreprise est fourni
if (!isset($_POST['id_fiche_entreprise']) || !is_numeric($_POST['id_fiche_entreprise'])) {
    $_SESSION['error'] = "ID de fiche entreprise invalide.";
    header('Location: ../../view/crudEntreprise/entrepriseRead.php');
    exit();
}

$id_fiche_entreprise = (int)$_POST['id_fiche_entreprise'];

try {
    // Initialiser le repository des fiches entreprise
    $ficheEntrepriseRepository = new FicheEntrepriseRepository();
    
    // Vérifier d'abord si la fiche entreprise existe
    $fiche = $ficheEntrepriseRepository->getFicheEntrepriseById($id_fiche_entreprise);
    
    if (!$fiche) {
        throw new Exception("La fiche entreprise spécifiée n'existe pas.");
    }

    // Supprimer la fiche entreprise
    $result = $ficheEntrepriseRepository->deleteFicheEntreprise($id_fiche_entreprise);
    
    if ($result) {
        $_SESSION['success'] = "La fiche entreprise a été supprimée avec succès.";
    } else {
        throw new Exception("Une erreur est survenue lors de la suppression de la fiche entreprise.");
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur lors de la suppression de la fiche entreprise : " . $e->getMessage();
}

// Rediriger vers la liste des entreprises
header('Location: ../../view/crudEntreprise/entrepriseRead.php');
exit();
