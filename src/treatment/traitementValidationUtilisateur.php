<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once('../bdd/config.php');
require_once('../repository/UtilisateurRepository.php');

// Vérifier que l'utilisateur connecté est un gestionnaire
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'Gestionnaire') {
    $_SESSION['error'] = "Accès refusé.";
    header('Location: ../../view/connexion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Méthode invalide.";
    header('Location: ../../view/crudUtilisateur/utilisateurAValider.php');
    exit();
}

$idUser = (int)($_POST['id_user'] ?? 0);
if ($idUser <= 0) {
    $_SESSION['error'] = "Utilisateur invalide.";
    header('Location: ../../view/crudUtilisateur/utilisateurAValider.php');
    exit();
}

try {
    $pdo = (new Config())->connexion();
    $repo = new UtilisateurRepository($pdo);

    $repo->update($idUser, [
        'est_valide'     => 1,
        'ref_validateur' => $_SESSION['utilisateur']['id_user'],
    ]);

    $_SESSION['success'] = "L'utilisateur a bien été validé.";
    header('Location: ../../view/crudUtilisateur/utilisateurAValider.php');
    exit();

} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur lors de la validation : " . $e->getMessage();
    header('Location: ../../view/crudUtilisateur/utilisateurAValider.php');
    exit();
}
