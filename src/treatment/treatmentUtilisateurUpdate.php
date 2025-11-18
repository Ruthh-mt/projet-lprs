<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un gestionnaire
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'Gestionnaire') {
    $_SESSION['error'] = "Accès refusé. Vous devez être connecté en tant que gestionnaire.";
    header('Location: ../../connexion.php');
    exit();
}

require_once __DIR__ . '/../modele/ModeleUtilisateur.php';
require_once __DIR__ . '/../repository/UtilisateurRepository.php';

// Vérifier si l'ID est présent
if (!isset($_POST['id_user']) || !is_numeric($_POST['id_user'])) {
    $_SESSION['error'] = "ID d'utilisateur invalide.";
    header('Location: ../../view/crudUtilisateur/utilisateurRead.php');
    exit();
}

$id_user = (int)$_POST['id_user'];
$utilisateurRepo = new UtilisateurRepository();

// Récupérer les données du formulaire
$data = [
    'prenom' => trim($_POST['prenom'] ?? ''),
    'nom' => trim($_POST['nom'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'role' => trim($_POST['role'] ?? ''),
    'promotion' => null,
    'matiere' => null
];

// Ajouter les champs spécifiques au rôle
if ($data['role'] === 'Étudiant') {
    $data['promotion'] = trim($_POST['promotion'] ?? '');
} elseif ($data['role'] === 'Professeur') {
    $data['matiere'] = trim($_POST['matiere'] ?? '');
}

// Validation des champs obligatoires
if (empty($data['prenom']) || empty($data['nom']) || empty($data['email']) || empty($data['role'])) {
    $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis.";
    header('Location: ../../view/crudUtilisateur/utilisateurUpdate.php?id=' . $id_user);
    exit();
}

// Validation de l'email
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "L'adresse email n'est pas valide.";
    header('Location: ../../view/crudUtilisateur/utilisateurUpdate.php?id=' . $id_user);
    exit();
}

// Mettre à jour l'utilisateur
if ($utilisateurRepo->update($id_user, $data)) {
    $_SESSION['success'] = "L'utilisateur a été mis à jour avec succès.";
} else {
    $_SESSION['error'] = "Une erreur est survenue lors de la mise à jour de l'utilisateur.";
}

header('Location: ../../view/crudUtilisateur/utilisateurRead.php');
exit();
