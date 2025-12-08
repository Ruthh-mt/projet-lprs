<?php
$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0] . '/public';
require_once '../../src/modele/ModeleEvenementUser.php';
require_once '../../src/repository/EvenementUserRepository.php';
require_once "../../src/bdd/config.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$eveUser = new  ModeleEvenementUser([
    "refUser" => $_SESSION["utilisateur"]["id_user"]]);
$eveUserRepo = new EvenementUserRepository();
$allEveSuperviseur = $eveUserRepo->getAllEvenementCreatedByUser($eveUser);
$allEveInscrit = $eveUserRepo->getAllEvenementUserInscrit($eveUser);
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<body>
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="../accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://giffiles.alphacoders.com/208/208817.gif" class="rounded-circle mx-3"
                 style="max-width: 48px;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="../accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="../evenements.php" class="btn btn-outline-light active me-2">Évènements</a></li>
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
                        <img src="<?= $prefix . htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil"
                             class="rounded-circle" style="max-width: 48px;object-fit:cover;">
                    <?php else: ?>
                        <i class="bi bi-person-circle fs-3 text-light"></i>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li><a class="dropdown-item text-primary" href="../account/accountRead.php">
                            <i class="bi bi-person"></i> Mon compte</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="../../src/treatment/traitementDeconnexion.php"><i
                                    class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
                </ul>
            <?php else: ?>
                <a href="#" class="d-inline-block text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-3 text-light"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li><a class="dropdown-item" href="../connexion.php"><i class="bi bi-box-arrow-in-right"></i>
                            Connexion</a>
                    </li>
                    <li><a class="dropdown-item" href="../inscription.php"><i class="bi bi-person-plus"></i>
                            Inscription</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</header>
<main>
    <section class="container banner bg-dark text-white text-center py-1 rounded">
        <h1>Mes Évènements</h1>
        <button class="btn btn-outline-light" id="showEveCreated">
            <i class="bi bi-calendar4-event"></i> Voir mes evenements Crées
        </button>
        <button class="btn btn-outline-light" id="showEveInscrit">
            <i class="bi bi-calendar4-event"></i> Voir mes evenements Inscrits
        </button>
    </section>
    <?php
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
    <!-- Section des eve ou que l'on a créer -->
    <section class="content my-4" id="eveCreated">
        <section class="container my-3">
            <article class="row my-3">
                <div class="justify-content-center card-group">
                    <?php
                    if (!empty($allEveSuperviseur)):
                    $count = 0;
                    $img = ["https://static.vecteezy.com/system/resources/previews/047/393/529/original/a-colorful-landscape-with-mountains-and-river-free-vector.jpg", "https://www.creativefabrica.com/wp-content/uploads/2024/10/17/Beautiful-Waterfall-Scene-Wallpaper-Graphics-108076283-1.jpg", "https://static.vecteezy.com/system/resources/previews/002/966/809/large_2x/sunset-waterfall-landscape-illustration-free-vector.jpg"];
                    foreach ($allEveSuperviseur

                    as $eveSupervise): ?>
                    <?php if ($count == 3) : ?>
                </div>
            </article>
        </section>
        <section class="container my-3">
            <article class="row my-3">
                <div class="justify-content-center card-group">
                    <?php $count = 0; ?>
                    <?php endif; ?>
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
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($eveSupervise->titre_eve) ?></h5>
                            <p class="card-text flex-grow-1 text-muted">
                                <?= htmlspecialchars(substr($eveSupervise->desc_eve, 0, 100)) ?>...
                            </p>
                            <a href="evenementRead.php?id=<?= htmlspecialchars($eveSupervise->id_evenement) ?>"
                               class="btn btn-primary mt-auto">
                                En savoir plus
                            </a>
                        </div>
                        <div class="card-footer text-muted small">
                            <?php
                            // tous sa c'est pour afficher la date au format que je veux et me donner l'intervalle entre maintenant et la date
                            $dateEve = $eveSupervise->date_heure_evenement;

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
                <h5> Il semblerait que vous avez crée aucun evenements</h5>
                <br>
                <p>Il s'agirait de contribuer au fun de ce lycée</p>
            </div>
        <?php endif; ?>
    </section>
    <!-- Section des eve ou l'on est inscrit -->
    <section class="content my-4" id="eveInscrit">
        <section class="container my-3">
            <article class="row my-3">
                <div class="justify-content-center card-group">
                    <?php
                    if (empty($allEveInscrit)):
                    $count = 0;
                    $img = ["https://static.vecteezy.com/system/resources/previews/047/393/529/original/a-colorful-landscape-with-mountains-and-river-free-vector.jpg", "https://www.creativefabrica.com/wp-content/uploads/2024/10/17/Beautiful-Waterfall-Scene-Wallpaper-Graphics-108076283-1.jpg", "https://static.vecteezy.com/system/resources/previews/002/966/809/large_2x/sunset-waterfall-landscape-illustration-free-vector.jpg"];
                    foreach ($allEveInscrit

                    as $eveInscrit): ?>
                    <?php if ($count == 3) : ?>
                </div>
            </article>
        </section>
        <section class="container my-3">
            <article class="row my-3">
                <div class="justify-content-center card-group">
                    <?php $count = 0; ?>
                    <?php endif; ?>
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
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($eveInscrit->titre_eve) ?></h5>
                            <p class="card-text flex-grow-1 text-muted">
                                <?= htmlspecialchars(substr($eveInscrit->desc_eve, 0, 100)) ?>...
                            </p>
                            <a href="evenementRead.php?id=<?= htmlspecialchars($eveInscrit->id_evenement) ?>"
                               class="btn btn-primary mt-auto">
                                En savoir plus
                            </a>
                        </div>
                        <div class="card-footer text-muted small">
                            <?php
                            // tous sa c'est pour afficher la date au format que je veux et me donner l'intervalle entre maintenant et la date
                            $dateEve = $eveInscrit->date_heure_evenement;

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
        <?php endif; else : ?>
            <div class="alert alert-dark alert-dismissible fade show">
                <h5> Il semblerait que vous vous etes inscrit a aucun evenements</h5>
                <br>
                <p>N'ayez pas peur, on ne mange pas souvent les gens </p>
            </div>
        <?php endif; ?>
    </section>
    <section class="container">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // au cas ou si quelqu'un passe par la
    // sa c'est du JQUERY grossomodo sa va permettre de manipuler les basile et class html et css avec du JS
    $(document).ready(function () {
//La les cours d'anglais vont vous servir
        $("#eveCreated").show();
        $("#eveInscrit").hide();

        // Onclick un peu comme en javaFX si on click sa va faire quelque chose
        $("#showEveInscrit").click(function () {
            // Dans notre cas montrer une partie et cacher l'autre
            $("#eveCreated").hide();
            $("#eveInscrit").show();
        });

        $("#showEveCreated").click(function () {
            $("#eveInscrit").hide();
            $("#eveCreated").show();
        });
    });
</script>
</body>
</html>