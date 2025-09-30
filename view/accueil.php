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
        <li class="nav-item"><a href="accueil.php" class="btn btn-outline-light active dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
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

<section class="container banner text-center bg-dark text-white text-center py-3 rounded">
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
          <div class="col card m-3" style="width: 18rem;">
               <img src="https://images.hdqwalls.com/wallpapers/mountains-minimalists-4k-vx.jpg" class="card-img-top" alt="...">
               <div class="card-body">
                    <h5 class="card-title">Titre de l'offre</h5>
                    <p class="card-text">Description de l'offre</p>
                    <a href="#" class="btn btn-primary">Voir l'offre</a>
               </div>
          </div>
          <div class="col card m-3" style="width: 18rem;">
               <img src="https://images.hdqwalls.com/wallpapers/mountains-minimalists-4k-vx.jpg" class="card-img-top" alt="...">
               <div class="card-body">
                    <h5 class="card-title">Titre de l'offre</h5>
                    <p class="card-text">Description de l'offre</p>
                    <a href="#" class="btn btn-primary">Voir l'offre</a>
               </div>
          </div>
          <div class="col card m-3" style="width: 18rem;">
               <img src="https://images.hdqwalls.com/wallpapers/mountains-minimalists-4k-vx.jpg" class="card-img-top" alt="...">
               <div class="card-body">
                    <h5 class="card-title">Titre de l'offre</h5>
                    <p class="card-text">Description de l'offre</p>
                    <a href="#" class="btn btn-primary">Voir l'offre</a>
               </div>
          </div>
          <div class="col card m-3" style="width: 18rem;">
               <img src="https://images.hdqwalls.com/wallpapers/mountains-minimalists-4k-vx.jpg" class="card-img-top" alt="...">
               <div class="card-body">
                    <h5 class="card-title">Titre de l'offre</h5>
                    <p class="card-text">Description de l'offre</p>
                    <a href="#" class="btn btn-primary">Voir l'offre</a>
               </div>
          </div>
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
          <div class="col card m-3" style="width: 18rem;">
               <img src="https://wallpapercave.com/wp/wp3403850.jpg" class="card-img-top" alt="...">
               <div class="card-body">
                    <h5 class="card-title">Titre de l'évènement</h5>
                    <p class="card-text">Description de l'évènement</p>
                    <a href="#" class="btn btn-primary">Voir l'évènement</a>
               </div>
          </div>
          <div class="col card m-3" style="width: 18rem;">
               <img src="https://wallpapercave.com/wp/wp3403850.jpg" class="card-img-top" alt="...">
               <div class="card-body">
                    <h5 class="card-title">Titre de l'évènement</h5>
                    <p class="card-text">Description de l'évènement</p>
                    <a href="#" class="btn btn-primary">Voir l'évènement</a>
               </div>
          </div>
          <div class="col card m-3" style="width: 18rem;">
               <img src="https://wallpapercave.com/wp/wp3403850.jpg" class="card-img-top" alt="...">
               <div class="card-body">
                    <h5 class="card-title">Titre de l'évènement</h5>
                    <p class="card-text">Description de l'évènement</p>
                    <a href="#" class="btn btn-primary">Voir l'évènement</a>
               </div>
          </div>
          <div class="col card m-3" style="width: 18rem;">
               <img src="https://wallpapercave.com/wp/wp3403850.jpg" class="card-img-top" alt="...">
               <div class="card-body">
                    <h5 class="card-title">Titre de l'évènement</h5>
                    <p class="card-text">Description de l'évènement</p>
                    <a href="#" class="btn btn-primary">Voir l'évènement</a>
               </div>
          </div>
          <div class="col card m-3" style="width: 18rem;">
               <img src="https://wallpapercave.com/wp/wp3403850.jpg" class="card-img-top" alt="...">
               <div class="card-body">
                    <h5 class="card-title">Titre de l'évènement</h5>
                    <p class="card-text">Description de l'évènement</p>
                    <a href="#" class="btn btn-primary">Voir l'évènement</a>
               </div>
          </div>
     </article>
</section>
</body>
</html>
