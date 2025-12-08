<?php
$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0].'/public';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('../src/bdd/config.php');
require_once('../src/modele/ModeleEvenement.php');
require_once('../src/repository/EvenementRepository.php');
$pdo = (new Config())->connexion();
$sql = "SELECT * from offre limit 5" ;
$stmt = $pdo->prepare($sql);
$stmt->execute();
$offres = $stmt->fetchAll(PDO::FETCH_ASSOC);
$eveRepo=New EvenementRepository();
$eveAcceuil=$eveRepo->showEvenementAcceuil();
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
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://giffiles.alphacoders.com/208/208817.gif" class="rounded-circle mx-3" style="max-width: 48px;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="accueil.php" class="btn btn-outline-light active dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
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
                    <li><a class="dropdown-item text-primary" href="connexion.php"><i class="bi bi-box-arrow-in-right"></i> Connexion</a></li>
                    <li><a class="dropdown-item text-success" href="inscription.php"><i class="bi bi-person-plus"></i> Inscription</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</header>
<?php if (isset($_SESSION['success'])): ?>
    <div class="container alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>
<section class="container banner bg-dark text-white text-center py-1 rounded">
    <h1>Bienvenue</h1>
</section>
<section class="container border rounded border-dark my-3">
    <h3 class="text-center py-3 text-center text-uppercase">Présentation</h3>
    <article class="article" style="text-align: justify;">
        <p>
            L'école souhaite développer un site web dédié à la gestion des anciens élèves (alumni) et aux relations avec les entreprises.
            Ce site servira de plateforme centrale pour renforcer les liens entre l'école, ses anciens élèves et les partenaires.
            L'objectif est de faciliter la communication, le réseautage et la collaboration, tout en offrant des services supplémentaires aux anciens élèves et aux entreprises.
        </p>
    </article>
</section>
<section class="container border rounded border-dark my-3">
    <h3 class="text-center py-3 text-center text-uppercase">Dernières offres postées</h3>
    <article class="article row" style="text-align: justify;">

        <?php foreach ($offres as $offre): ?>
        <div class="col card m-3" style="width: 18rem;">
            <img src="https://images.hdqwalls.com/wallpapers/mountains-minimalists-4k-vx.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title"><?= $offre['titre'] ?></h5>
                <p class="card-text"><?= $offre['description'] ?> </p>
                <a href="#" class="btn btn-primary">Voir l'offre</a>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="col card m-3" style="width: 18rem;">
            <img src="https://images.hdqwalls.com/wallpapers/mountains-minimalists-4k-vx.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Titre de l'offre</h5>
                <p class="card-text">Description de l'offre</p>
                <a href="#" class="btn btn-primary">Voir l'offre</a>
            </div>
        </div>



    </article>
</section>
<section class="container border rounded border-dark my-3">
    <h3 class="text-center py-3 text-center text-uppercase">Derniers évènements créés</h3>
    <article class="article row" style="text-align: justify;">
        <?php $img =["https://img.freepik.com/premium-vector/moon-night-landscape-vector-illustration-pine-tree-night-lake_538866-357.jpg","https://tse1.mm.bing.net/th/id/OIP.MO4C99oM9oCl533Y4mq17gHaEK?pid=ImgDet&w=184&h=103&c=7&dpr=1,3&o=7&rm=3","https://www.creativefabrica.com/wp-content/uploads/2024/10/18/Moonlight-Scenery-Illustration-Wallpaper-Graphics-108135561-1.jpg" ]?>
        <?php foreach ($eveAcceuil as $eve):?>
        <div class="col card m-3" style="width: 18rem;">
            <img src="<?php try {
                echo htmlspecialchars($img[random_int(0, 2)]);
            } catch (\Random\RandomException $e) {
                echo $e->getMessage();
            } ?>" class="card-img-top" alt="..." >
            <div class="card-body">
                <h5 class="card-title"><?=htmlspecialchars($eve->titre_eve)?></h5>
                <p class="card-text d-inline-block text-truncate" style="max-width: 150px;"><?=htmlspecialchars($eve->desc_eve)?></p>
                <a href="crudEvenement/evenementRead.php?id=<?=htmlspecialchars($eve->id_evenement)?>" class="btn btn-primary">Voir l'évènement</a>
            </div>
        </div>
        <?php endforeach;?>
    </article>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>