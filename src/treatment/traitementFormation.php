<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../repository/FormationRepository.php';
require_once __DIR__ . '/../modele/ModeleFormation.php';

$repo = new FormationRepository();

$action = $_POST['action'] ?? $_GET['action'] ?? null;

if ($action === null) {
    header('Location: ../../view/crudFormation/formationRead.php');
    exit;
}

switch ($action) {

    case 'create':
        $nom = trim($_POST['nom'] ?? '');

        if ($nom === '') {
            $_SESSION['error'] = "Le nom de la formation est obligatoire.";
            header('Location: ../../view/crudFormation/formationCreate.php');
            exit;
        }

        $formation = new ModeleFormation([
            'idFormation' => null,
            'nom'         => $nom,
        ]);

        $ok = $repo->create($formation);

        if ($ok) {
            $_SESSION['success'] = "Formation créée avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la création de la formation.";
        }

        header('Location: ../../view/crudFormation/formationRead.php');
        exit;

    case 'update':
        $id = $_POST['id_formation'] ?? $_GET['id_formation'] ?? $_GET['id'] ?? null;
        $id = (int)$id;

        if ($id <= 0) {
            $_SESSION['error'] = "Identifiant de formation invalide.";
            header('Location: ../../view/crudFormation/formationRead.php');
            exit;
        }

        $nom = trim($_POST['nom'] ?? '');

        if ($nom === '') {
            $_SESSION['error'] = "Le nom de la formation est obligatoire.";
            header('Location: ../../view/crudFormation/formationUpdate.php?id=' . $id);
            exit;
        }

        $ok = $repo->update($id, ['nom' => $nom]);

        if ($ok) {
            $_SESSION['success'] = "Formation mise à jour avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour de la formation.";
        }

        header('Location: ../../view/crudFormation/formationRead.php');
        exit;

    case 'delete':
        $id = $_POST['id_formation'] ?? $_GET['id_formation'] ?? $_GET['id'] ?? null;
        $id = (int) $id;

        if ($id <= 0) {
            $_SESSION['error'] = "Identifiant de formation invalide.";
            header('Location: ../../view/crudFormation/formationRead.php');
            exit;
        }

        try {
            $ok = $repo->delete($id);

            if ($ok) {
                $_SESSION['success'] = "Formation supprimée avec succès.";
            } else {
                $_SESSION['error'] = "Aucune formation supprimée (ID introuvable ou déjà supprimé).";
            }
        } catch (\PDOException $e) {
            $_SESSION['error'] = "Suppression impossible : la formation est probablement utilisée ailleurs.";
        }

        header('Location: ../../view/crudFormation/formationRead.php');
        exit;

    default:
        $_SESSION['error'] = "Action inconnue pour les formations.";
        header('Location: ../../view/crudFormation/formationRead.php');
        exit;
}
