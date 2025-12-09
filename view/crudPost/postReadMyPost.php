<?php
$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0] . '/public';
require_once '../../src/modele/ModelePost.php';
require_once '../../src/repository/PostRepository.php';
require_once '../../src/modele/ModeleReponse.php';
require_once '../../src/repository/ReponseRepository.php';
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
    <title>FORUM • LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
        <li class="nav-item"><a href="../evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="../emplois.php" class="btn btn-outline-light me-2">Emplois</a></li>
        <li class="nav-item"><a href="../forum.php" class="btn btn-outline-light active me-2">Forum</a></li>
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
                    <li><a class="dropdown-item text-primary" href="../account/accountRead.php"><i
                                    class="bi bi-person"></i> Mon compte</a></li>
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
                    <li><a class="dropdown-item" href="../connexion.php"><i class="bi bi-box-arrow-in-right"></i> Connexion</a>
                    </li>
                    <li><a class="dropdown-item" href="../inscription.php"><i class="bi bi-person-plus"></i>
                            Inscription</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</header>
<section class="container banner bg-dark text-white text-center py-1 rounded">
    <h1>Mes posts et reponses</h1>
    <a class="btn btn-outline-light" href="../forum.php" role="button"><i class="bi bi-chat-dots"></i>
        Retour au forum</a>
    <div class="btn-group" role="group" aria-label="Basic outlined example">
        <button type="button" class="btn btn-outline-light" id="showPostUser">Mes posts</button>
        <button type="button" class="btn btn-outline-light" id="showReponseUser">Mes reponses</button>
    </div>
</section>
<main>
    <section class="container">
        <?php if (!empty($_SESSION["toastr"])) {
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
        <div class="d-grid gap-2">
            <a class="btn btn-outline-success text-uppercase my-3" href="postCreate.php" role="button">Créer un
                post</a>
        </div>

        <section class="container" id="postUser">
            <?php
            $post = new ModelePost(["refUser" => $_SESSION["utilisateur"]["id_user"]]);
            $postRepository = new PostRepository();
            $allPostUser = $postRepository->getAllPostByUser($post);
            if (empty($allPostUser)) :?>
                <h5 class="alert alert-dark alert-dismissible fade show"> Il semblerait que la communication soit
                    surcoter,
                    Soyer le premier à communiquer </h5>
            <?php else : ?>
                <?php foreach ($allPostUser as $post):
                    $username = $postRepository->findUsername($post->id_post); ?>
                    <div class="card">
                        <div class="card-header"><i class="bi bi-person-circle"><?= " " ?></i>
                            <?= $username["prenom"] . ' ' . $username["nom"] ?></i></div>
                        <div class="card-body">
                            <h5 class="card-title"><?= $post->titre_post ?> | <span
                                        class="badge text-bg-dark"><?= $post->canal ?></span>
                            </h5>
                            <p class="col-20 text-truncate" id="contenu"><?= $post->contenu_post ?></p>
                            <a href="postRead.php?id=<?= $post->id_post ?>" class="btn btn-primary">Voir plus</a>
                        </div>
                    </div> <br>
                <?php endforeach;
            endif; ?>
        </section>
    </section>
    <section class="container" id="reponseUser">
        <?php
        $reponse = new ModeleReponse(["refUser" => $_SESSION["utilisateur"]["id_user"]]);
        $reponseRepository = new ReponseRepository();
        $allReponseUser = $reponseRepository->getAllReponseByUser($reponse);
        if (empty($allReponseUser)) :?>
            <h5 class="alert alert-dark alert-dismissible fade show"> Il semblerait que la communication soit surcoter,
                Soyer le premier à communiquer </h5>
        <?php else : ?>
            <?php foreach ($allReponseUser as $reponse):
                $userName = $reponseRepository->findUsernameMyReponse($reponse->id_reponse); ?>
                <div class="card" >
                    <div class="card-header">
                        <?php if ($avatar): ?>
                            <img src="<?= $prefix . htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>"
                                 alt="Photo de profil"
                                 class="rounded-circle" style="max-width: 48px;object-fit:cover;">
                        <?php else: ?>
                            <i class="bi bi-person-circle fs-3 text-dark"></i>
                        <?php endif; ?>
                        <?= $userName["prenom"] . " " . $userName["nom"] ?>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?= htmlspecialchars($reponse->contenu_reponse) ?></p>
                    </div>
                    <div class="card-footer text-body-secondary">
                        <?= htmlspecialchars($reponse->date_heure) ?>
                        <a class="btn btn-primary" href="postRead.php?id=<?=$reponse->ref_post?>"> Voir le post</a>
                    </div>
                </div>
            <?php endforeach;
        endif; ?>
    </section>
    <section>
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
    </section>
</main>
<script>
    $(document).ready(function () {
        $("#postUser").show();
        $("#reponseUser").hide();

        $("#showPostUser").click(function () {
            $("#postUser").show();
            $("#reponseUser").hide();
        });
        $("#showReponseUser").click(function () {
            $("#postUser").hide();
            $("#reponseUser").show();
        });
    });
</script>
</body>
</html>


