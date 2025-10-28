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
        <li class="nav-item"><a href="evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="annuaire.php" class="btn btn-outline-light active me-2">Annuaire</a></li>
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
<section class="container">
     <form class="container-fluid">
          <div class="input-group">
               <span class="input-group-text" id="basic-addon1">Recherche</span>
               <input type="text" class="form-control" placeholder="Utilisateur" aria-label="Username"
                      aria-describedby="basic-addon1"/>
               <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
          </div>
     </form>
     <article class="row my-3">
          <div class="card-group">
               <div class="card">
                    <img src="https://wallpaperbat.com/img/804383-flat-2d-4k-wallpaper-top-free-flat-2d-4k-background.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Prénom NOM</h5>
                         <p class="card-text">
                         <div class="card">
                              <ul class="list-group list-group-flush">
                                   <li class="list-group-item"><i class="bi bi-cake"></i> 01/01/2000</li>
                                   <li class="list-group-item"><i class="bi bi-envelope-at"></i> 01.23.45.67.89</li>
                                   <li class="list-group-item"><i class="bi bi-telephone"></i> adresse@email.fr</li>
                              </ul>
                         </div>
                         </p>
                    </div>
               </div>
               <div class="card">
                    <img src="https://wallpapercave.com/wp/wp12959808.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Prénom NOM</h5>
                         <p class="card-text">
                         <div class="card">
                              <ul class="list-group list-group-flush">
                                   <li class="list-group-item"><i class="bi bi-cake"></i> 01/01/2000</li>
                                   <li class="list-group-item"><i class="bi bi-envelope-at"></i> 01.23.45.67.89</li>
                                   <li class="list-group-item"><i class="bi bi-telephone"></i> adresse@email.fr</li>
                              </ul>
                         </div>
                         </p>
                    </div>
               </div>
               <div class="card">
                    <img src="https://images.hdqwalls.com/wallpapers/minimal-morning-mountains-4k-ja.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Prénom NOM</h5>
                         <p class="card-text">
                         <div class="card">
                              <ul class="list-group list-group-flush">
                                   <li class="list-group-item"><i class="bi bi-cake"></i> 01/01/2000</li>
                                   <li class="list-group-item"><i class="bi bi-envelope-at"></i> 01.23.45.67.89</li>
                                   <li class="list-group-item"><i class="bi bi-telephone"></i> adresse@email.fr</li>
                              </ul>
                         </div>
                         </p>
                    </div>
               </div>
          </div>
     </article>
</section>
<section class="container">
     <article class="row my-3">
          <div class="card-group">
               <div class="card">
                    <img src="https://images.hdqwalls.com/wallpapers/minimal-morning-mountains-4k-ja.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Prénom NOM</h5>
                         <p class="card-text">
                         <div class="card">
                              <ul class="list-group list-group-flush">
                                   <li class="list-group-item"><i class="bi bi-cake"></i> 01/01/2000</li>
                                   <li class="list-group-item"><i class="bi bi-envelope-at"></i> 01.23.45.67.89</li>
                                   <li class="list-group-item"><i class="bi bi-telephone"></i> adresse@email.fr</li>
                              </ul>
                         </div>
                         </p>
                    </div>
               </div>
               <div class="card">
                    <img src="https://wallpapercave.com/wp/wp12959808.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Prénom NOM</h5>
                         <p class="card-text">
                         <div class="card">
                              <ul class="list-group list-group-flush">
                                   <li class="list-group-item"><i class="bi bi-cake"></i> 01/01/2000</li>
                                   <li class="list-group-item"><i class="bi bi-envelope-at"></i> 01.23.45.67.89</li>
                                   <li class="list-group-item"><i class="bi bi-telephone"></i> adresse@email.fr</li>
                              </ul>
                         </div>
                         </p>
                    </div>
               </div>
               <div class="card">
                    <img src="https://wallpaperbat.com/img/804383-flat-2d-4k-wallpaper-top-free-flat-2d-4k-background.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Prénom NOM</h5>
                         <p class="card-text">
                         <div class="card">
                              <ul class="list-group list-group-flush">
                                   <li class="list-group-item"><i class="bi bi-cake"></i> 01/01/2000</li>
                                   <li class="list-group-item"><i class="bi bi-envelope-at"></i> 01.23.45.67.89</li>
                                   <li class="list-group-item"><i class="bi bi-telephone"></i> adresse@email.fr</li>
                              </ul>
                         </div>
                         </p>
                    </div>
               </div>
          </div>
     </article>
</section>
<section class="container">
     <article class="row my-3">
          <div class="card-group">
               <div class="card">
                    <img src="https://images.hdqwalls.com/wallpapers/minimal-morning-mountains-4k-ja.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Prénom NOM</h5>
                         <p class="card-text">
                         <div class="card">
                              <ul class="list-group list-group-flush">
                                   <li class="list-group-item"><i class="bi bi-cake"></i> 01/01/2000</li>
                                   <li class="list-group-item"><i class="bi bi-envelope-at"></i> 01.23.45.67.89</li>
                                   <li class="list-group-item"><i class="bi bi-telephone"></i> adresse@email.fr</li>
                              </ul>
                         </div>
                         </p>
                    </div>
               </div>
               <div class="card">
                    <img src="https://wallpaperbat.com/img/804383-flat-2d-4k-wallpaper-top-free-flat-2d-4k-background.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Prénom NOM</h5>
                         <p class="card-text">
                         <div class="card">
                              <ul class="list-group list-group-flush">
                                   <li class="list-group-item"><i class="bi bi-cake"></i> 01/01/2000</li>
                                   <li class="list-group-item"><i class="bi bi-envelope-at"></i> 01.23.45.67.89</li>
                                   <li class="list-group-item"><i class="bi bi-telephone"></i> adresse@email.fr</li>
                              </ul>
                         </div>
                         </p>
                    </div>
               </div>
               <div class="card">
                    <img src="https://wallpapercave.com/wp/wp12959808.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5 class="card-title">Prénom NOM</h5>
                         <p class="card-text">
                         <div class="card">
                              <ul class="list-group list-group-flush">
                                   <li class="list-group-item"><i class="bi bi-cake"></i> 01/01/2000</li>
                                   <li class="list-group-item"><i class="bi bi-envelope-at"></i> 01.23.45.67.89</li>
                                   <li class="list-group-item"><i class="bi bi-telephone"></i> adresse@email.fr</li>
                              </ul>
                         </div>
                         </p>
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