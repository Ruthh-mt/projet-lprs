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
        <li class="nav-item"><a href="annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
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
            <a href="connexion.php" class="btn btn-outline-success active">Connexion</a>
            <a href="inscription.php" class="btn btn-outline-primary">Inscription</a>
        <?php endif; ?>
    </div>
</header>

<main class="container my-5">
     <div class="row justify-content-center">
          <div class="col-md-6">
               <h2 class="text-center mb-4">Connexion à votre compte</h2>
              <?php if (isset($_SESSION['success'])): ?>
                  <div class="container alert alert-success alert-dismissible fade show" role="alert">
                      <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                  </div>
              <?php endif; ?>
               <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                         <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
               <?php endif; ?>
               <form action="../src/treatment/traitementConnexion.php" method="post">
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