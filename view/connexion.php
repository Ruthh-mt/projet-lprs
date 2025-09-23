<?php session_start(); ?>
<!doctype html>
<html lang="fr">
<head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>CONNEXION • LPRS</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header
     class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
     <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
          <a href="accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
               <img src="https://media.tenor.com/1DV7nkfj5OkAAAAM/blobgodeto-blobdance.gif" class="rounded-circle mx-3"
                    style="max-width: 15%; height: auto;">
               <div class="fs-4 text-light">LPRS</div>
          </a>
     </div>
     <ul class="nav col mb-2 justify-content-center mb-md-0">
          <li class="nav-item"><a href="accueil.php" class="btn btn-outline-primary dropdown me-2">Accueil</a></li>
          <li class="nav-item"><a href="#" class="btn btn-outline-light me-2">Évènements</a></li>
          <li class="nav-item"><a href="#" class="btn btn-outline-light me-2">Annuaire</a></li>
          <li class="nav-item"><a href="#" class="btn btn-outline-light me-2">Liste des élèves</a></li>
     </ul>

     <div class="col-2 btn-group md-3 me-3 text-end" role="group" aria-label="Boutons utilisateur">
          <?php if (isset($_SESSION['utilisateur'])): ?>
               <a href="#" class="btn btn-outline-primary">MON COMPTE</a>
               <a href="../src/treatment/deconnexion.php" class="btn btn-outline-danger">DÉCONNEXION</a>
          <?php else: ?>
               <a href="connexion.php" class="btn btn-outline-success">CONNEXION</a>
               <a href="inscription.php" class="btn btn-outline-primary">INSCRIPTION</a>
          <?php endif; ?>
     </div>
</header>

<main class="container my-5">
     <div class="row justify-content-center">
          <div class="col-md-6">
               <h2 class="text-center mb-4">Connexion à votre compte</h2>

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

               <form action="../src/treatment/connexion.php" method="post">
                    <div class="form-floating mb-3">
                         <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="Adresse email" required autocomplete="email">
                         <label for="floatingEmail">Adresse email</label>
                    </div>
                    <div class="form-floating mb-3">
                         <input type="password" name="mot_de_passe" class="form-control" id="floatingPassword" placeholder="Mot de passe" required autocomplete="current-password">
                         <label for="floatingPassword">Mot de passe</label>
                    </div>
                    <div class="d-grid gap-2">
                         <button type="submit" class="btn btn-outline-success">SE CONNECTER</button>
                         <a href="inscription.php" class="btn btn-outline-primary">S'INSCRIRE</a>
                        <a href="../src/treatment/traitementVerificationEmail.php" class="btn btn-outline-primary">Mot de passe oublié </a>
                    </div>
               </form>
          </div>
     </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>