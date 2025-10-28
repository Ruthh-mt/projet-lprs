<?php
require_once '../bdd/config.php';
require_once '../modele/FormationsModel.php';
require_once '../repository/FormationRepository.php';

    $errors = [];
    $formation = null;

    if (!isset($pdo) || !($pdo instanceof PDO)) {
        $errors['internal'] = 'Base de données non configurée.';
    } else {
        $repo = new FormationRepository($db);

        $id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id_formation']) ? (int)$_POST['id_formation'] : 0);
        if ($id <= 0) {
            $errors['id'] = 'Identifiant invalide.';
        } else {
            $formation = $repo->find($id);
            if (!$formation) {
                $errors['notfound'] = 'Formation introuvable.';
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
            if (!csrf_check($_POST['csrf_token'] ?? '')) {
                $errors['csrf'] = 'Jeton CSRF invalide.';
            } else {
                try {
                    $repo->delete($id);
                    header('Location: FormationRead.php?deleted=1');
                    exit;
                } catch (Throwable $e) {
                    $errors['internal'] = 'Erreur lors de la suppression : ' . $e->getMessage();
                }
            }
        }
    }
}
