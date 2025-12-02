<?php
require_once "../../src/modele/ModelePost.php";
require_once "../../src/modele/ModeleReponse.php";
require_once "../../src/repository/PostRepository.php";
require_once "../../src/repository/ReponseRepository.php";
require_once "../../src/bdd/config.php";
$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0] . '/public';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('Aucun post sélectionné'); window.location.href='../forum.php';</script>";
    exit;
}
if (!isset ($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}
$nbReponseParPage = 10;
$debut = ($page - 1) * $nbReponseParPage;
$postRepo = new PostRepository();
$post = $postRepo->getPostById(new ModelePost(["idPost" => $id]));
$reponseRepo = new ReponseRepository();
$reponses = $reponseRepo->getAllReponsebyPostId($post->getidPost(), $debut, $nbReponseParPage);
$nbTotalReponse = $reponseRepo->countAllReponseByPost($post->getidPost()) / $nbReponseParPage;

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détail d’un post • LPRS</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .section-offre {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .offre-header {
            background-color: #212529;
            color: white;
            border-radius: .75rem .75rem 0 0;
            padding: 1.5rem;
            border-bottom: 2px solid #0d6efd;
        }

        .offre-header h2 {
            margin: 0;
        }

        label {
            font-weight: 600;
            margin-bottom: 6px;
        }

        input[type=text],
        input[type=number],
        textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.9);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

    </style>
</head>
<body>

<header class="d-flex flex-wrap align-items-center justify-content-between py-3 mb-4 border-bottom bg-dark px-4">
    <div class="d-flex align-items-center">
        <a href="../accueil.php" class="d-inline-flex text-decoration-none align-items-center">
            <img src="https://media.tenor.com/1DV7nkfj5OkAAAAM/blobgodeto-blobdance.gif"
                 class="rounded-circle mx-3"
                 style="max-width: 40px; height: auto;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <div>
        <a href="../forum.php" class="btn btn-outline-light">Retour aux Forum</a>
    </div>
</header>
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
<!-- SECTION DETAIL -->
<div class="container mb-5">
    <div class="section-offre">
        <div class="offre-header d-flex justify-content-between align-items-center">
            <?php $username = $postRepo->findUsername($post->getIdPost());
            $avatar = $_SESSION['utilisateur']['avatar'] ?? null; ?>
            <h5 class="fw-bold">  <?php if ($avatar): ?>
                    <img src="<?= $prefix . htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil"
                         class="rounded-circle" style="max-width: 48px;object-fit:cover;">
                <?php else: ?>
                    <i class="bi bi-person-circle fs-3 text-light"></i>
                <?php endif; ?> <?= htmlspecialchars($username["prenom"] . " " . $username["nom"]) ?></h5>
            <h2 class="fw-bold"> <?= htmlspecialchars($post->getTitrePost()) ?></h2>
            <button type="button" class="btn btn-outline-light" onclick="window.location.href='../forum.php'">
                <i class="bi bi-arrow-left-circle"></i> Retour
            </button>
        </div>
        <div class="mb-3">
            <label for="contenu"><span
                        class="badge text-bg-dark"><?= $post->getCanal() ?></span></label>
            <textarea readonly class="form-control"
                      id="contenu"><?= htmlspecialchars($post->getContenuPost()) ?></textarea>
        </div>
        <?php
        $createur = $post->getRefUser();
        if (!empty($_SESSION["utilisateur"]) && $createur == $_SESSION['utilisateur']['id_user']) {
            echo '<div class="text-center mt-3">
                <a href="postUpdate.php?id=' . $post->getIdPost() . '" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i> Modifier le post
                </a>
            </div>';
        }
        ?>
        <h5>Commentaire</h5>
        <hr>
        <br>
        <button class="btn btn-primary" type="button" <?php if (empty($_SESSION["utilisateur"])) : ?>
            data-bs-toggle="modal" data-bs-target="#connectezVousModal"
        <?php else: ?>
            data-bs-toggle="collapse" data-bs-target="#showCommentCreateForm" aria-expanded="false" aria-controls="showCommentCreateForm"
        <?php endif; ?>
        >Commenter
        </button>
        <div class="collapse collapse-horizontal" id="showCommentCreateForm">
            <form method="post" action="../../src/treatment/traitementAjoutReponse">
                <input type="hidden" name="refPost" value="<?= htmlspecialchars($post->getidPost()); ?>">
                <input type="hidden" name="refUser"
                       value="<?= htmlspecialchars($_SESSION['utilisateur']['id_user']) ?>">
                <label for="contenu"> Entrer un commentaire</label>
                <textarea class="form-control" id="contenu" name="contenu_reponse"></textarea>
                <input type="submit" class="btn btn-primary" name="submit" value="Publier"/>
            </form>
        </div>
        <br>
        <?php
        foreach ($reponses as $reponse) :
            $userpost = $reponseRepo->findUsernameReponse(new ModeleReponse(["idReponse" => $reponse->id_reponse,
                "refPost" => $reponse->ref_post]));
            ?>
            <br>
            <div class="card" style="width: 25rem;">
                <div class="card-header">
                    <?php if ($avatar): ?>
                        <img src="<?= $prefix . htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil"
                             class="rounded-circle" style="max-width: 48px;object-fit:cover;">
                    <?php else: ?>
                        <i class="bi bi-person-circle fs-3 text-dark"></i>
                    <?php endif; ?>
                    <?= $userpost["prenom"] . " " . $userpost["nom"] ?>
                    <?php if (!empty($_SESSION['utilisateur']) && $reponse->ref_user == $_SESSION['utilisateur']['id_user'] || $_SESSION['utilisateur']['role'] == "Gestionnaire")  : ?>
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <button class="btn dropdown-item" data-bs-toggle="collapse"
                                            data-bs-target="#showCommentUpdateForm-<?= $reponse->id_reponse ?>"
                                            aria-expanded="false" aria-controls="showCommentUpdateForm">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </li>
                                <li>
                                    <button class="btn dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#deleteReponseModal"
                                            data-id-reponse="<?= htmlspecialchars($reponse->id_reponse) ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </li>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <p class="card-text"><?= htmlspecialchars($reponse->contenu_reponse) ?></p>
                </div>
                <div class="card-footer text-body-secondary">
                    <?= htmlspecialchars($reponse->date_heure) ?>
                </div>
            </div>
            <div class="collapse collapse-horizontal" id="showCommentUpdateForm-<?= $reponse->id_reponse ?>"
                 style="width:25%;">
                <form method="post" action="../../src/treatment/traitementUpdateReponse.php">
                    <input type="hidden" name="refPost" value="<?= htmlspecialchars($post->getidPost()); ?>">
                    <input type="hidden" name="idReponse" value="<?= htmlspecialchars($reponse->id_reponse); ?>">
                    <input type="hidden" name="refUser"
                           value="<?= htmlspecialchars($_SESSION['utilisateur']['id_user']) ?>">
                    <label for="contenu"> Entrer un commentaire</label>
                    <textarea class="form-control" id="contenu"
                              name="contenu_reponse"><?= $reponse->contenu_reponse ?></textarea>
                    <input type="submit" class="btn btn-primary" name="submit" value="Modifier"/>
                </form>
            </div>
            <br>
        <?php endforeach; ?>
    </div>
    <div class="modal fade" id="connectezVousModal" tabindex="-1" aria-labelledby="connectezVousModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="connectezVousModalLabel">PTDR t'es qui ? </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Veuillez vous connectez
                </div>
                <div class="modal-footer">
                    <a href="../connexion.php" type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        Se connecter
                    </a>
                    <a href="../inscription.php" type="button" class="btn btn-secondary"
                       data-bs-dismiss="modal">S'inscrire
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- MODALE DE CONFIRMATION -->
    <div class="modal fade" id="deleteReponseModal" tabindex="-1" aria-labelledby="deleteReponseModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteReponseModalLabel">Confirmer la suppression de la reponse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer cette reponse ? Cette action est irréversible.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form method="post" action="../../src/treatment/traitementDeleteReponse.php">
                        <input type="hidden" name="idPost" id="refPost" value="<?= $post->getIdPost() ?>">
                        <input type="hidden" name="idReponse" id="idreponse" value="">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-exclamation-octagon"></i> Supprimer définitivement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <section class="container">
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <a class="page-link" href="postRead.php?id=<?= $post->getidPost()?>?page=<?php if ($page > 1) {
                        echo $page - 1;
                    } else {
                        echo $page;
                    } ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($pages = 1; $pages <= $nbTotalReponse + 1; $pages++):
                    if ($pages == $page) : ?>
                        <li class="page-item active">
                            <a class="page-link" href="postRead.php?id=<?=$post->getidPost()?>?page=<?= $pages ?>"
                               aria-current="page"><?= $pages ?></a>
                        </li>
                    <?php else : ?>
                        <li class="page-item">
                            <a class="page-link"
                               href="postRead.php?id=<?= $post->getidPost()?>?page=<?= $pages ?>"><?= $pages ?></a>
                        </li>
                    <?php endif;
                endfor; ?>
                <li class="page-item">
                    <a class="page-link" href="postRead.php?id=<?= $post->getidPost() ?>?page=<?= $page + 1 ?>"
                       aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </section>
    <script>
        function autoResize(textarea) {
            textarea.style.height = 'auto'; // Reset height
            textarea.style.height = textarea.scrollHeight + 'px'; // Set to content height
        }

        // Get the textarea
        const ta = document.getElementById('contenu');

        // Adjust height on input
        ta.addEventListener('input', () => autoResize(ta));

        // Adjust height on page load for prefilled content
        window.addEventListener('load', () => autoResize(ta));

        document.addEventListener('DOMContentLoaded', function () {
            var confirmModal = document.getElementById('deleteReponseModal');
            if (!confirmModal) return;

            confirmModal.addEventListener('show.bs.modal', function (event) {
                // bouton qui a déclenché l'ouverture
                var button = event.relatedTarget;
                var idreponse = button.getAttribute('data-id-reponse') || '';
                // injecter dans le modal
                document.getElementById('idreponse').value = idreponse;
            });
        });
    </script>
</body>
</html>
