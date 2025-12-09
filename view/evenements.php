<?php
$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0] . '/public';
require_once '../src/modele/ModeleEvenement.php';
require_once '../src/repository/EvenementRepository.php';
require_once "../src/bdd/config.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset ($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}
$nbEvenementParPage = 9;
$debut = ($page - 1) * $nbEvenementParPage;
$evenementRepository = new EvenementRepository();
$allEvenement = $evenementRepository->getAllEvenement($debut, $nbEvenementParPage);
$nbTotalEve = $evenementRepository->countAllEvenement() / $nbEvenementParPage;

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EVENEMENTS • LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-4.0.0-rc.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
        <li class="nav-item"><a href="evenements.php" class="btn btn-outline-light active me-2">Évènements</a></li>
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
<main>
    <section class="container banner bg-dark text-white text-center py-1 rounded">
        <h1>Évènements</h1>
        <?php
        if (isset($_SESSION['utilisateur'])) :?>
            <a class="btn btn-outline-light" href="crudEvenement/evenementReadMyEvent.php" role="button"><i
                        class="bi bi-calendar4-event"></i> Mes evenements</a>
            <?php if ($_SESSION["utilisateur"]["role"] === "Professeur") : ?>
                <a class="btn btn-outline-light" href="crudEvenement/evenementValidate.php" role="button"><i
                            class="bi bi-calendar4-event"></i> Voir les evenement a valider</a>
            <?php endif;
        endif; ?>
    </section>
    <section>
        <section class="container">
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <div class="d-grid gap-2">
                    <a class="btn btn-outline-success text-uppercase my-3" href="crudEvenement/evenementCreate.php"
                       role="button">Créer un évènement</a>
                </div>
            <?php endif;
            if (!empty($_SESSION["toastr"])) {
                $type = $_SESSION["toastr"]["type"];
                $message = $_SESSION["toastr"]["message"];
                echo '<script>
        // Set the options that I want
        toastr.options = {
            "closeButton": true,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-bottom-full-width",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "slideDown",
            "hideMethod": "slideUp"
        }
        toastr.' . $type . '("' . $message . '");


    </script>';
                unset($_SESSION['toastr']);
            }
            ?>
            <section class="container my-3">
                <article class="row my-3">
                    <div class="justify-content-center card-group">
                        <?php
                        if (!empty($allEvenement)):
                        $count = 0;
                        $img = ["https://wallpaper.dog/large/20516113.png", "https://wallpaperswide.com/download/flat_design_illustration-wallpaper-3000x2000.jpg", "https://image.civitai.com/xG1nkqKTMzGDvpLrqFT7WA/3f811964-bed4-4072-b204-1c37e575fefb/width=1200/3f811964-bed4-4072-b204-1c37e575fefb.jpeg"];
                        foreach ($allEvenement

                        as $evenement): ?>
                        <?php if ($count == 3) : ?>
                    </div>
                </article>
            </section>
        <section class="container my-3">
        <article class="row my-3">
        <div class="justify-content-center card-group">
        <?php $count = 0;
        endif; ?>
        <div class="card shadow-sm"> <!---style="width: 320px; height: 430px; flex: 0 0 auto;"-->
            <img src="<?php try {
                echo htmlspecialchars($img[random_int(0, 2)]);
            } catch (\Random\RandomException $e) {
                echo $e->getMessage();
            } ?>"
                 class="card-img-top"
                 alt="Image événement"
                 style="height: 230px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title fw-bold"><?= htmlspecialchars($evenement->titre_eve) ?></h5>
                <p class="card-text flex-grow-1 text-muted">
                    <?= htmlspecialchars(substr($evenement->desc_eve, 0, 100)) ?>...
                </p>
                <a href="crudEvenement/evenementRead.php?id=<?= htmlspecialchars($evenement->id_evenement) ?>"
                   class="btn btn-primary mt-auto">
                    En savoir plus
                </a>
            </div>
            <div class="card-footer text-muted small">
                <?php
                // tous sa c'est pour afficher la date au format que je veux et me donner l'intervalle entre maintenant et la date
                $dateEve = $evenement->date_heure_evenement;

                try {
                    // instanciation des class datetime
                    $today = new DateTime(); // la date de maintenant
                    $eventDate = new DateTime($dateEve);

                    $formattedEventDate = $eventDate->format("d/m/Y");// on va mettre la date au format que l'on veut

                    // je viens de decouvrir donc je saurais pas trop expliquer mais grace a nitea classe datetime
                    $interval = $today->diff($eventDate);//on a une methode qui permet d'avoir la difference entre 2 date


                    if ($eventDate > $today) {// on va determiner l'evenement est dans le futur
                        echo "Date de l'evenement : $formattedEventDate <br>";
                        echo "Jour avant l'evenement : " . $interval->days . " jour(s)\n";
                    } elseif ($eventDate < $today) {// on va determiner l'evenement est dans le passée
                        echo "Date de l'evenement : $formattedEventDate <br>";
                        echo "L'evenement etait :" . $interval->days . " jour(s) avant.\n";
                    } else {// ou si c'est maintenant
                        echo "Date de l'evenement : $formattedEventDate <br>";
                        echo "L'evenement est aujourd'hui!\n";
                    }
                } catch (Exception $e) {// on fait un petit try catch juste pour chopper les erreur
                    echo "Error: Mauvais format. Utiliser YYYY-MM-DD ." . $e->getMessage() . "\n";
                }
                // oui oeut etre qu'il y avait un solution plus simple mais sur le moment je ne l'ai pas trouvé
                ?>


            </div>
        </div>

        <?php $count++;
        endforeach; ?>
            <?php if ($count > 0 && $count < 3): ?>
                </div>
                </article>
                </section>
            <?php endif; ?>
            <?php else : ?>
            <div class="alert alert-dark alert-dismissible fade show">
                <h5> Il semblerait qu'il n'y a pas
                    d'evenements</h5>
                <br>
                <p>Soyez le/la premier/e à lancer le pas et
                    crée votre evenement</p>
            </div>
                <?php endif; ?>


                <section class="container">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item">
                                <a class="page-link" href="evenements.php?page=<?php if ($page > 1) {
                                    echo $page - 1;
                                } else {
                                    echo $page;
                                } ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php for ($pages = 1; $pages <= $nbTotalEve + 1; $pages++):
                                if ($pages == $page) : ?>
                                    <li class="page-item active">
                                        <a class="page-link" href="evenements.php?page=<?= $pages ?>"
                                           aria-current="page"><?= $pages ?></a>
                                    </li>
                                <?php else : ?>
                                    <li class="page-item">
                                        <a class="page-link" href="evenements.php?page=<?= $pages ?>"><?= $pages ?></a>
                                    </li>
                                <?php endif;
                            endfor; ?>
                            <li class="page-item">
                                <a class="page-link" href="evenements.php?page=<?= $page + 1 ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>