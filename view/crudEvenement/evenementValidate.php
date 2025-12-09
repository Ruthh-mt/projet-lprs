<?php
require_once '../../src/modele/ModeleEvenement.php';
require_once '../../src/repository/evenementRepository.php';
require_once "../../src/bdd/config.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
            <img src="https://gifdb.com/images/high/yellow-lively-blob-dancing-emoji-cwouznave21jqjlk.gif"
                 class="rounded-circle mx-3"
                 style="max-width: 15%; height: auto;">
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
    <div class="col-2 btn-group md-3 me-3 text-end" role="group" aria-label="Boutons utilisateur">
        <?php if (isset($_SESSION['utilisateur'])): ?>
            <a href="../account/accountRead.php" class="btn btn-outline-primary">Mon compte</a>
            <a href="../../src/treatment/traitementDeconnexion.php" class="btn btn-outline-danger">Déconnexion</a>
        <?php else: ?>
            <a href="../connexion.php" class="btn btn-outline-success">Connexion</a>
            <a href="../inscription.php" class="btn btn-outline-primary">Inscription</a>
        <?php endif; ?>
    </div>
</header>
<section class=" bg-dark text-white text-center py-1 rounded">
    <h1>Évènements en attente de validation</h1>
    <a class="btn btn-outline-light" href="../evenements.php" role="button">Retour au evenements</a>
</section>
<main>
    <?php if (!empty($_SESSION["toastr"])):
        $type = $_SESSION["toastr"]["type"];
        $message = $_SESSION["toastr"]["message"];
        ?>
        <script>
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
            toastr.<?=$type?>("<?=$message?>")


        </script>


        <?php unset($_SESSION['toastr']);
    endif; ?>
    <section class="container">
            <?php if (!isset($_SESSION['utilisateur'])): ?>
                <h5 class="alert alert-danger alert-dismissible fade show"> Vous êtes pas connecté. Veuillez vous
                    connecter</h5>
            <?php else : ?>
            <section class="container my-3">
                <article class="row my-3">
                    <div class="justify-content-center card-group">
                <?php $evenementRepository = new EvenementRepository();
                $allEvenement = $evenementRepository->getAllEvenementNonValide(new ModeleEvenement(["status" => "en attente", "estValide" => 0]));
                $img = ["https://static.vecteezy.com/system/resources/previews/000/203/128/original/vector-abstract-landscape-illustration.jpg", "https://static.vecteezy.com/system/resources/previews/000/206/117/non_2x/vector-landscape-illustration.jpg", "https://static.vecteezy.com/system/resources/previews/000/517/616/large_2x/vector-landscape-illustration.png"];
                if (!empty($allEvenement)) :
                $count = 0;
                foreach ($allEvenement

                as $evenement) :
                if ($count == 3) : ?>
            </div>
            </article>
        </section>
            <section class="container my-3">
                <article class="row my-3">
                    <div class="justify-content-center card-group">
                    <?php $count = 0;
                    endif; ?>
                    <div class="card shadow-sm" style="width: 320px; height: 430px; flex: 0 0 auto;">
                        <img src="<?php try {
                            echo htmlspecialchars($img[random_int(0, 2)]);
                        } catch (\Random\RandomException $e) {
                            echo $e->getMessage();
                        } ?>"
                             class="card-img-top"
                             alt="Image événement"
                             style="height: 180px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($evenement->titre_eve) ?></h5>
                            <p class="card-text flex-grow-1 text-muted">
                                <?= htmlspecialchars(substr($evenement->desc_eve, 0, 100)) ?>...
                            </p>
                            <a href="evenementRead.php?id=<?= $evenement->id_evenement ?>"
                               class="btn btn-primary mt-auto">
                                En savoir plus
                            </a>
                        </div>
                        <div class="card-footer text-muted small">
                            <?php
                            // Set your event date here (format: YYYY-MM-DD)
                            $dateEve = $evenement->date_heure_evenement;

                            try {
                                // Create DateTime objects
                                $today = new DateTime(); // Current date
                                $eventDate = new DateTime($dateEve);

                                // Format the event date as dd/mm/yyyy
                                $formattedEventDate = $eventDate->format("d/m/Y");

                                // Calculate the difference
                                $interval = $today->diff($eventDate);

                                // Determine if the event is in the future or past
                                if ($eventDate > $today) {
                                    echo "Date de l'evenement : {$formattedEventDate}\n";
                                    echo "Jour avant l'evenement : " . $interval->days . " jour(s)\n";
                                } elseif ($eventDate < $today) {
                                    echo "Date de l'evenement : {$formattedEventDate}\n";
                                    echo "L'evenement etait :" . $interval->days . " jour(s) avant.\n";
                                } else {
                                    echo "Date de l'evenement : {$formattedEventDate}\n";
                                    echo "L'evenement est aujourd'hui!\n";
                                }
                            } catch (Exception $e) {
                                // Handle invalid date format
                                echo "Error: Mauvais format. Utiliser YYYY-MM-DD.\n";
                            }

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
                <h5> Il semblerait qu'il n'y a pas d'evenements
                    a valider</h5>
            </div>
        <?php endif;
        endif; ?>


    </section>

    </section>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
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
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>