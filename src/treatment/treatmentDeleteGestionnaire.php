<?php
// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'Gestionnaire') {
    $_SESSION['error'] = "Accès refusé. Vous devez être connecté en tant qu'administrateur.";
    header('Location: ../../connexion.php');
    exit();
}

// Vérifier si l'ID est fourni
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    $_SESSION['error'] = "ID de gestionnaire invalide.";
    header('Location: ../../view/crudGestionnaire/gestionnaireRead.php');
    exit();
}

$id = (int)$_POST['id'];

// Empêcher l'auto-suppression
if (isset($_SESSION['utilisateur']['id_user']) && $id === (int)$_SESSION['utilisateur']['id_user']) {
    $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte.";
    header('Location: ../../view/crudGestionnaire/gestionnaireRead.php');
    exit();
}

// Inclure les fichiers nécessaires
require_once __DIR__ . '/../repository/GestionnaireRepository.php';

try {
    $gestionnaireRepo = new GestionnaireRepository();
    
    // Vérifier si le gestionnaire existe
    $gestionnaire = $gestionnaireRepo->findById($id);
    
    if (!$gestionnaire) {
        $_SESSION['error'] = "Le gestionnaire demandé n'existe pas ou n'est plus un gestionnaire.";
    } else {
        // Rétrograder le gestionnaire en étudiant
        $result = $gestionnaireRepo->downgradeToEtudiant($id);
        
        if ($result) {
            $_SESSION['success'] = "Le gestionnaire a été rétrogradé avec succès au rôle d'étudiant.";
        } else {
            // Vérifier si le gestionnaire existe toujours
            if ($gestionnaireRepo->findById($id)) {
                $_SESSION['error'] = "Impossible de rétrograder le gestionnaire. Vérifiez vos permissions.";
            } else {
                $_SESSION['error'] = "Le gestionnaire n'existe pas ou a déjà été rétrogradé.";
            }
        }
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur technique : " . $e->getMessage();
}

// Rediriger vers la liste des gestionnaires
header('Location: ../../view/crudGestionnaire/gestionnaireRead.php');
exit();
