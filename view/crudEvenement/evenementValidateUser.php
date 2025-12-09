<?php
require_once "../../src/modele/ModeleEvenement.php";
require_once "../../src/modele/ModeleEvenementUser.php";
require_once "../../src/repository/EvenementRepository.php";
require_once "../../src/repository/EvenementUserRepository.php";
require_once "../../src/bdd/config.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('Aucun événement sélectionné'); window.location.href='../evenements.php';</script>";
    exit;
}
$evenementRepo = new EvenementRepository();
$evenementUserRepository = new EvenementUserRepository();
$evenement = $evenementRepo->getAnEvenement(new ModeleEvenement(["idEvenement" => $id]));
$eveUser = new ModeleEvenementUser(["refEvenement" => $evenement->id_evenement, "estSuperviseur" => 0]);
$allUser = $evenementUserRepository->getAllInscritsByEvenement($eveUser);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EVENEMENTS • LPRS</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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
    <h1>Liste des inscrit a l'evenement : <?= $evenement->titre_eve ?></h1>
    <?php
    if (!isset($_SESSION['utilisateur'])) {
        echo '<br>';
    } ?>
    <a class="btn btn-outline-light" href="evenementRead.php?id=<?= $evenement->id_evenement ?>" role="button">Retour à
        l'evenement </a>
</section>
<main>
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
    <section class="container">
        <br>
        <section class="container my-4">
            <?php if (!isset($_SESSION['utilisateur'])): ?>
                <h5 class="alert alert-danger alert-dismissible fade show"> Vous êtes pas connecté. Veuillez vous
                    connecter</h5>

            <?php else : ?>
                <?php if (!empty($allUser)) : ?>
                    <?php foreach ($allUser as $user) : ?>
                        <div class="card bg-base-100 w-96 shadow-sm">
                            <div class="card-body">
                                <h2 class="card-title"><?= htmlspecialchars(strtoupper($user->nom)) . " " . htmlspecialchars($user->prenom) ?></h2>
                                <p><?= htmlspecialchars($user->role) ?></p>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#confirmModal"
                                        data-ref-user="<?= htmlspecialchars($user->ref_user) ?>"
                                        data-nom="<?= htmlspecialchars($user->nom) ?>"
                                        data-prenom="<?= htmlspecialchars($user->prenom) ?>">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    <?php endforeach;
                else :?>
                    <div class="alert alert-dark alert-dismissible fade show">
                        <h5 > Il semblerait qu'il n'y ai pas de participant a cet evenement
                            <br>
                            Allez faire votre promotion de votre evenement
                        </h5>
                    </div>
                <?php endif;
            endif; ?>
        </section>
        <!-- MODALE DE CONFIRMATION -->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="confirmModalLabel">Confirmer la desinscription</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Êtes-vous sûr de vouloir desinscrire <span id="modalUserNom"></span> <span
                                id="modalUserPrenom"></span> ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <form method="post" action="../../src/treatment/traitementValidationUserEvenement.php">
                            <input type="hidden" name="idevenement"
                                   value="<?= htmlspecialchars($evenement->id_evenement) ?>">
                            <input type="hidden" name="refuser" id="refUser" value="">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-exclamation-octagon"></i> Confirmer la desinscription
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var confirmModal = document.getElementById('confirmModal');
        if (!confirmModal) return;

        confirmModal.addEventListener('show.bs.modal', function (event) {
            // bouton qui a déclenché l'ouverture
            var button = event.relatedTarget;

            // récupérer les data-*
            var refUser = button.getAttribute('data-ref-user') || '';
            var nom = button.getAttribute('data-nom') || '';
            var prenom = button.getAttribute('data-prenom') || '';

            // injecter dans le modal
            document.getElementById('modalUserNom').textContent = nom;
            document.getElementById('modalUserPrenom').textContent = prenom;
            document.getElementById('refUser').value = refUser;
        });
    });

</script>
</body>
</html>