<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['utilisateur'])) {
     header('Location: ../Connexion.php');
     exit();
}
$utilisateur = $_SESSION['utilisateur'];
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
          <a href="../accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
               <img src="https://media.tenor.com/1DV7nkfj5OkAAAAM/blobgodeto-blobdance.gif"
                    class="rounded-circle mx-3"
                    style="max-width: 15%; height: auto;">
               <div class="fs-4 text-light text-uppercase">LPRS</div>
          </a>
     </div>
     <ul class="nav col mb-2 justify-content-center mb-md-0">
          <li class="nav-item"><a href="../accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
          <li class="nav-item"><a href="../evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
          <li class="nav-item"><a href="../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
          <li class="nav-item"><a href="../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
          <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Gestionnaire'): ?>
               <li class="nav-item">
                    <a href="../administration.php" class="btn btn-outline-warning me-2">Administration</a>
               </li>
          <?php endif; ?>
     </ul>
     <div class="col-2 btn-group md-3 me-3 text-end" role="group" aria-label="Boutons utilisateur">
          <?php if (isset($_SESSION['utilisateur'])): ?>
               <a href="accountRead.php" class="btn btn-outline-primary active">Mon compte</a>
               <a href="../../src/treatment/traitementDeconnexion.php" class="btn btn-outline-danger">Déconnexion</a>
          <?php else: ?>
               <a href="../connexion.php" class="btn btn-outline-success">Connexion</a>
               <a href="../inscription.php" class="btn btn-outline-primary">Inscription</a>
          <?php endif; ?>
     </div>
</header>
<div class="container my-4">
     <div class="row">
          <h2 class="text-center text-uppercase mb-4">Mon compte</h2>
     </div>

     <div class="row justify-content-center">
          <div class="col-md-6">
               <div class="card shadow-sm">
                    <div class="card-body">
                         <h5 class="card-title text-center mb-4">Informations personnelles</h5>
                         <ul class="list-group list-group-flush">
                              <li class="list-group-item"><strong>Prénom :</strong> <?= htmlspecialchars($utilisateur['prenom']) ?></li>
                              <li class="list-group-item"><strong>Nom :</strong> <?= htmlspecialchars($utilisateur['nom']) ?></li>
                              <li class="list-group-item"><strong>Email :</strong> <?= htmlspecialchars($utilisateur['email']) ?></li>
                              <li class="list-group-item"><strong>Rôle :</strong> <?= isset($utilisateur['role']) ? htmlspecialchars($utilisateur['role']) : 'Non renseigné' ?></li>
                         </ul>

                         <div class="d-grid gap-2 mt-4">
                              <a href="AccountEdit.php" class="btn btn-outline-warning">Modifier</a>
                              <a href="AccountDelete.php" class="btn btn-outline-danger">Supprimer</a>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoIIfJcLyjU29tka7Sk3YSA8l7IgGKmFckcImFV8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>



</body>
</html>