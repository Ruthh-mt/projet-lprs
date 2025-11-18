<?php
$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0].'/public';
session_start (); ?>
<!doctype html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ACCUEIL • LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">

</head>
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://giffiles.alphacoders.com/208/208817.gif" class="rounded-circle mx-3" style="max-width: 48px;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="emplois.php" class="btn btn-outline-light active me-2">Emplois</a></li>
        <li class="nav-item"><a href="forum.php" class="btn btn-outline-light me-2">Forum</a></li>
        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Gestionnaire'): ?>
            <li class="nav-item">
                <a href="administration.php" class="btn btn-outline-warning me-2">Administration</a>
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
                    <li><a class="dropdown-item text-primary" href="account/accountRead.php"><i class="bi bi-person"></i> Mon compte</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../src/treatment/traitementDeconnexion.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
                </ul>
            <?php else: ?>
                <a href="#" class="d-inline-block text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-3 text-light"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li><a class="dropdown-item" href="connexion.php"><i class="bi bi-box-arrow-in-right"></i> Connexion</a></li>
                    <li><a class="dropdown-item" href="inscription.php"><i class="bi bi-person-plus"></i> Inscription</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</header>
<body>
<div class="wrap">
    <div class="card">
        <br>
        <h1>Merci pour votre candidature !</h1>
        <br>
        <p>Votre dossier a bien été transmis à notre équipe . Nous vous contacterons si votre profil correspond.</p>
        <a class="button" href="emplois.php">Page d'accueil</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>