<?php
$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0].'/public';
session_start();

if (!function_exists('e')) {
    function e(?string $v): string { return htmlspecialchars($v ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

$me       = $_SESSION['utilisateur'] ?? null;
$userId   = (int)($me['id'] ?? $me['id_user'] ?? $me['id_utilisateur'] ?? 0);
$email    = (string)($me['email'] ?? '');
$prenom   = (string)($me['prenom'] ?? $me['first_name'] ?? '');
$nom      = (string)($me['nom'] ?? $me['last_name'] ?? '');
$fullname = trim(($prenom . ' ' . $nom)) ?: 'Mon compte';

$action = '../../src/treatment/traitementAccountDelete.php';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ACCUEIL • LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<header
        class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="../accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://i.pinimg.com/originals/a0/50/1e/a0501e0c5659dcfde397299e4234e75a.gif" class="mx-3" style="max-width: 48px; height: auto;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="../accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="../evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="../emplois.php" class="btn btn-outline-light me-2">Emplois</a></li>
        <li class="nav-item"><a href="../forum.php" class="btn btn-outline-light me-2">Forum</a></li>
        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Gestionnaire'): ?>
            <li class="nav-item">
                <a href="../administration.php" class="btn btn-outline-warning me-2">Administration</a>
            </li>
        <?php endif; ?>
    </ul>
    <div class="col-2 text-end me-3">
        <div class="dropdown">
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <?php $avatar = $_SESSION['utilisateur']['avatar'] ?? null; ?>
                <a href="#" class="d-inline-block text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <?php if ($avatar): ?>
                        <img src="<?= $prefix.htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil" class="rounded-circle" style="max-width: 48px;object-fit:cover;">
                    <?php else: ?>
                        <i class="bi bi-person-circle fs-3 text-light"></i>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li><a class="dropdown-item text-primary" href="../account/accountRead.php"><i class="bi bi-person"></i> Mon compte</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../../src/treatment/traitementDeconnexion.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
                </ul>
            <?php else: ?>
                <a href="#" class="d-inline-block text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-3 text-light"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li><a class="dropdown-item" href="../connexion.php">Connexion</a></li>
                    <li><a class="dropdown-item" href="../inscription.php">Inscription</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Suppression définitive du compte</strong>
                </div>
                <div class="card-body">

                    <?php if (!empty($_SESSION['flash'])): ?>
                        <?php foreach ($_SESSION['flash'] as $f): ?>
                            <div class="alert alert-<?= e($f['type'] ?? 'info') ?> my-2"><?= e($f['message'] ?? '') ?></div>
                        <?php endforeach; unset($_SESSION['flash']); ?>
                    <?php endif; ?>

                    <?php if (empty($me)): ?>
                        <div class="alert alert-warning" role="alert">
                            Vous devez être connecté pour accéder à cette page.
                        </div>
                        <div class="d-flex gap-2">
                            <li><a class="dropdown-item text-primary" href="../connexion.php"><i class="bi bi-box-arrow-in-right"></i> Connexion</a></li>
                            <li><a class="dropdown-item text-success" href="../inscription.php"><i class="bi bi-person-plus"></i> Inscription</a></li>
                        </div>
                    <?php else: ?>

                        <p class="mb-3">
                            Bonjour <strong><?= e($fullname) ?></strong> (<?= e($email) ?>).<br>
                            Cette action est <u>irréversible</u> et supprimera votre compte ainsi que ses liens.
                        </p>

                        <form method="post" action="<?= e($action) ?>">
                            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                            <input type="hidden" name="user_id" value="<?= e((string)$userId) ?>">

                            <div class="mb-3">
                                <label class="form-label">Confirmez votre email</label>
                                <input type="email" class="form-control" name="confirm_email" placeholder="<?= e($email) ?>" required>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" id="ack" name="ack" required>
                                <label class="form-check-label" for="ack">Je comprends que la suppression est définitive.</label>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" name="confirm" value="1" class="btn btn-danger">
                                    <i class="bi bi-trash3 me-1"></i> Supprimer définitivement mon compte
                                </button>
                                <a href="../account/accountRead.php" class="btn btn-outline-secondary">Annuler</a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
