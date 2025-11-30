<?php
$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0] . '/public';
require_once '../src/modele/ModeleUtilisateur.php';
require_once '../src/repository/UtilisateurRepository.php';
require_once "../src/bdd/config.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset ($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}
$nbUtilisateurParPage = 9;
$debut = ($page - 1) * $nbUtilisateurParPage;
$utilisateurRepository = new UtilisateurRepository();
$allUtilisateur = $utilisateurRepository->getAllUtilisateurs($debut, $nbUtilisateurParPage);
$nbTotalUser = $utilisateurRepository->countAllUtilisateurs() / $nbUtilisateurParPage;

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ANNUAIRE • LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://giffiles.alphacoders.com/208/208817.gif" class="rounded-circle mx-3"
                 style="max-width: 48px;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="annuaire.php" class="btn btn-outline-light active me-2">Annuaire</a></li>
        <li class="nav-item"><a href="listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="emplois.php" class="btn btn-outline-light me-2">Emplois</a></li>
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
                        <img src="<?= $prefix . htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil"
                             class="rounded-circle" style="max-width: 48px;object-fit:cover;">
                    <?php else: ?>
                        <i class="bi bi-person-circle fs-3 text-light"></i>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li><a class="dropdown-item text-primary" href="account/accountRead.php"><i
                                    class="bi bi-person"></i> Mon compte</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="../src/treatment/traitementDeconnexion.php"><i
                                    class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
                </ul>
            <?php else: ?>
                <a href="#" class="d-inline-block text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-3 text-light"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li><a class="dropdown-item" href="connexion.php"><i class="bi bi-box-arrow-in-right"></i> Connexion</a>
                    </li>
                    <li><a class="dropdown-item" href="inscription.php"><i class="bi bi-person-plus"></i>
                            Inscription</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</header>
<section class="container banner bg-dark text-white text-center py-1 rounded">
    <h1>Annuaire</h1>
</section>
<section class="container">
    <form class="container-fluid" action="" method="post">
        <div class="input-group">
            <span class="input-group-text" id="basic-addon1">Recherche</span>
            <input type="text" class="form-control" placeholder="Utilisateur" aria-label="Username"
                   aria-describedby="basic-addon1"/>
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
        </div>
    </form>
</section>
<section class="container">
    <article class="row my-3">
        <div class="card-group">
            <?php
            if (!empty($allUtilisateur)):
            $count = 0;
            $img = ["https://wallpaperbat.com/img/804383-flat-2d-4k-wallpaper-top-free-flat-2d-4k-background.jpg", "https://wallpapercave.com/wp/wp12959808.jpg", "https://images.hdqwalls.com/wallpapers/minimal-morning-mountains-4k-ja.jpg"];
            foreach ($allUtilisateur as $user):
             if ($count == 3) : ?>
        </div>
    </article>
</section>
<section class="container">
    <article class="row my-3">
        <div class="card-group">
            <?php $count =0; endif;?>
            <div class="card">
                <img src="<?= htmlspecialchars($img[$count]) ?>"
                     class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($user->prenom." ".strtoupper($user->nom) )?></h5>
                    <p class="card-text">
                    <div class="card">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="bi bi-envelope-at"></i> <?= htmlspecialchars($user->email) ?></li>
                        </ul>
                    </div>
                    </p>
                </div>
            </div>
            <?php $count++;
            endforeach;
            else :?>
                <div class="alert alert-dark alert-dismissible fade show">
                    <h5> Il semblerait qu'il n'y a pas d'utilisateur</h5>
                    <br>
                    <p>Allez faire votre propagande et faite des adeptes</p>";
                </div>
            <?php endif; ?>
            <section class="container"
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item">
                        <a class="page-link" href="annuaire.php?page=<?php if($page>1){echo$page-1;}else{echo $page; } ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($pages=1 ; $pages <= $nbTotalUser+1; $pages++): ?>
                        <li class="page-item"><a class="page-link" href="annuaire.php?page=<?= $pages ?>"><?= $pages ?></a></li>
                    <?php endfor; ?>
                    <li class="page-item">
                        <a class="page-link" href="annuaire.php?page=<?= $page +1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>