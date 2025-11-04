<?php
if (!defined('TRAITEMENT_FORMATION_CREATE_INCLUDED')) {
    define('TRAITEMENT_FORMATION_CREATE_INCLUDED', true);

    require_once '../bdd/config.php';;
    require_once '../modele/ModeleFormation.php';;
    require_once '../repository/FormationRepository.php';

    $errors = [];
    $old = ['nom' => ''];

    if (!isset($pdo) || !($pdo instanceof PDO)) {
        $errors['internal'] = 'Base de données non configurée (db.php manquant ou $pdo absent).';
    } else {
        $repo = new FormationRepository($db);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_check($_POST['csrf_token'] ?? '')) {
                $errors['csrf'] = 'Jeton CSRF invalide.';
            }

            $nom = trim((string)($_POST['nom'] ?? ''));

            $old['nom'] = $nom;

            if ($nom === '') {
                $errors['nom'] = 'Le nom de la formation est requis.';
            } elseif (mb_strlen($nom) > 50) {
                $errors['nom'] = 'Le nom doit faire au maximum 50 caractères.';
            }

            if (empty($errors)) {
                $model = new ModeleFormation(null, $nom);
                try {
                    $newId = $repo->create($model);
                    header('Location: FormationRead.php?created=1');
                    exit;
                } catch (Throwable $e) {
                    $errors['internal'] = 'Erreur lors de la création : ' . $e->getMessage();
                }
            }
        }
    }
}
