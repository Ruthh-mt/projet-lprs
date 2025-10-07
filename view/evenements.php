<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ACCUEIL • LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
</head>
<body>
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://media.tenor.com/1DV7nkfj5OkAAAAM/blobgodeto-blobdance.gif"
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
<section class="container">
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
     <div class="d-grid gap-2">
        <a  class="btn btn-outline-success text-uppercase my-3" href="crudEvenement/evenementCreate.php" role="button">Ajouter un évènement</a>
         <!---<button class="btn btn-outline-success text-uppercase my-3" type="button">Ajouter un évènement</button>--->
     </div>
     <article class="row my-3">
          <div class="card-group">
               <div class="card">
                    <img src="https://wallpapers.com/images/hd/4k-vector-snowy-landscape-p7u7m7qyxich2h31.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Card title</h5>
                         <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                    </div>
                    <div class="card-footer">
                         <small class="text-body-secondary">Last updated 3 mins ago</small>
                    </div>
               </div>
               <div class="card">
                    <img src="https://wallpapers.com/images/hd/4k-vector-japan-landscape-91fou43c3r7lglnr.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Card title</h5>
                         <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                    </div>
                    <div class="card-footer">
                         <small class="text-body-secondary">Last updated 3 mins ago</small>
                    </div>
               </div>
               <div class="card">
                    <img src="https://i.redd.it/4syggbto2km01.png" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Card title</h5>
                         <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
                    </div>
                    <div class="card-footer">
                         <small class="text-body-secondary">Last updated 3 mins ago</small>
                    </div>
               </div>
          </div>
     </article>
</section>
<section class="container">
     <article class="row my-3">
          <div class="card-group">
               <div class="card">
                    <img src="https://i.redd.it/4syggbto2km01.png" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Card title</h5>
                         <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                    </div>
                    <div class="card-footer">
                         <small class="text-body-secondary">Last updated 3 mins ago</small>
                    </div>
               </div>
               <div class="card">
                    <img src="https://wallpapers.com/images/hd/4k-vector-snowy-landscape-p7u7m7qyxich2h31.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Card title</h5>
                         <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                    </div>
                    <div class="card-footer">
                         <small class="text-body-secondary">Last updated 3 mins ago</small>
                    </div>
               </div>
               <div class="card">
                    <img src="https://wallpapers.com/images/hd/4k-vector-japan-landscape-91fou43c3r7lglnr.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Card title</h5>
                         <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
                    </div>
                    <div class="card-footer">
                         <small class="text-body-secondary">Last updated 3 mins ago</small>
                    </div>
               </div>
          </div>
     </article>
</section>
<section class="container">
     <article class="row my-3">
          <div class="card-group">
               <div class="card">
                    <img src="https://wallpapers.com/images/hd/4k-vector-snowy-landscape-p7u7m7qyxich2h31.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Card title</h5>
                         <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                    </div>
                    <div class="card-footer">
                         <small class="text-body-secondary">Last updated 3 mins ago</small>
                    </div>
               </div>
               <div class="card">
                    <img src="https://i.redd.it/4syggbto2km01.png" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Card title</h5>
                         <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                    </div>
                    <div class="card-footer">
                         <small class="text-body-secondary">Last updated 3 mins ago</small>
                    </div>
               </div>
               <div class="card">
                    <img src="https://wallpapers.com/images/hd/4k-vector-japan-landscape-91fou43c3r7lglnr.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Card title</h5>
                         <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
                    </div>
                    <div class="card-footer">
                         <small class="text-body-secondary">Last updated 3 mins ago</small>
                    </div>
               </div>
          </div>
     </article>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>