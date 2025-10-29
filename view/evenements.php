<?php
require_once '../src/modele/ModeleEvenement.php';
require_once '../src/repository/evenementRepository.php';
require_once "../src/bdd/config.php";

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
</head>
<body>
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://gifdb.com/images/high/yellow-lively-blob-dancing-emoji-cwouznave21jqjlk.gif"
                 class="rounded-circle mx-3"
                 style="max-width: 15%; height: auto;">
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
    <div class="col-2 btn-group md-3 me-3 text-end" role="group" aria-label="Boutons utilisateur">
        <?php if (isset($_SESSION['utilisateur'])): ?>
            <a href="account/accountRead.php" class="btn btn-outline-primary">Mon compte</a>
            <a href="../src/treatment/traitementDeconnexion.php" class="btn btn-outline-danger">Déconnexion</a>
        <?php else: ?>
            <a href="connexion.php" class="btn btn-outline-success">Connexion</a>
            <a href="inscription.php" class="btn btn-outline-primary">Inscription</a>
        <?php endif; ?>
    </div>
</header>
<section class="container banner bg-dark text-white text-center py-1 rounded">
    <h1>Évènements</h1>
</section>
<main>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<section class="container">
    <?php if (isset($_SESSION['utilisateur'])): ?>
     <div class="d-grid gap-2">
        <a  class="btn btn-outline-success text-uppercase my-3" href="crudEvenement/evenementCreate.php" role="button">Créer un évènement</a>
     </div>
    <?php endif; ?>
    <section class="container my-4">
        <?php
    if(!isset($_SESSION['utilisateur'])){
    echo'<h5 class="alert alert-danger alert-dismissible fade show"> Vous êtes pas connecté. Veuillez vous connecter</h5>';
    }
    else {
        echo'<div class="d-flex flex-wrap justify-content-start gap-4">';
            $evenementRepository = new EvenementRepository();
            $allEvenement = $evenementRepository->getAllEvenement();

            foreach ($allEvenement as $evenement) {
                echo '<div class="card shadow-sm" style="width: 320px; height: 430px; flex: 0 0 auto;">
                    <img src="https://wallpapers.com/images/hd/4k-vector-snowy-landscape-p7u7m7qyxich2h31.jpg"
                         class="card-img-top"
                         alt="Image événement"
                         style="height: 180px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold">'.htmlspecialchars($evenement["titre_eve"]).'</h5>
                        <p class="card-text flex-grow-1 text-muted">
                            '.htmlspecialchars(substr($evenement["desc_eve"], 0, 100)).'...
                        </p>
                        <a href="../view/crudEvenement/evenementRead.php?id='. $evenement["id_evenement"].'"
                           class="btn btn-primary mt-auto">
                            En savoir plus
                        </a>
                    </div>
                    <div class="card-footer text-muted small">
                        Dernière mise à jour : '.date("d/m/Y H:i").'
                    </div>
                </div>';
            }
        echo'</div>';
    } ?>

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>