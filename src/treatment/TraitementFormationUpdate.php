<?php
require_once '../bdd/config.php';;
require_once '../modele/FormationsModel.php';
require_once '../repository/FormationRepository.php';

    $errors = [];
    $old = ['nom' => ''];
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
            } else {
                // initialiser $old depuis la base
                $old['nom'] = $formation->nom;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
            if (!csrf_check($_POST['csrf_token'] ?? '')) {
                $errors['csrf'] = 'Jeton CSRF invalide.';
            }

            $nom = trim((string)($_POST['nom'] ?? ''));
            $old['nom'] = $nom;

            if ($nom === '') {
                $errors['nom'] = 'Le nom est requis.';
            } elseif (mb_strlen($nom) > 50) {
                $errors['nom'] = 'Le nom doit faire au maximum 50 caractères.';
            }

            if (empty($errors)) {
                $formation->nom = $nom;
                try {
                    $repo->update($formation);
                    header('Location: FormationRead.php?updated=1');
                    exit;
                } catch (Throwable $e) {
                    $errors['internal'] = 'Erreur lors de la mise à jour : ' . $e->getMessage();
                }
            }
        }
    }
}
