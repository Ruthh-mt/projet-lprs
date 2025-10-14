<?php
if (session_status() === PHP_SESSION_NONE) {
     session_start();
}
if (!function_exists('e')) {
     function e(?string $v): string {
          return htmlspecialchars($v ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
     }
}

require_once '../../src/bdd/config.php';
require_once '../../src/repository/utilisateurRepository.php';

$cfg  = new Config();
$pdo  = $cfg->connexion();
$repo = new UtilisateurRepository($pdo);

try {
     $utilisateurs = $repo->findAll();
} catch (Throwable $e) {
     http_response_code(500);
     $utilisateurs = [];
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
<header
     class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 border-bottom bg-dark">
     <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
          <a href="accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
               <img src="https://media.tenor.com/1DV7nkfj5OkAAAAM/blobgodeto-blobdance.gif"
                    class="rounded-circle mx-3"
                    style="max-width: 15%; height: auto;">
               <div class="fs-4 text-light text-uppercase">LPRS • ADMIN</div>
          </a>
     </div>
     <ul class="nav col mb-2 justify-content-center mb-md-0">
          <li class="nav-item"><a href="../accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
          <li class="nav-item"><a href="../evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
          <li class="nav-item"><a href="../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
          <li class="nav-item"><a href="../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
          <li class="nav-item"><a href="emplois.php" class="btn btn-outline-light me-2">Emplois</a></li>
          <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Gestionnaire'): ?>
               <li class="nav-item">
                    <a href="../administration.php" class="btn btn-outline-warning active me-2">Administration</a>
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
<nav class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom text-white bg-dark">
     <div class="nav col mb-2 justify-content-center mb-md-0">
          <div class="btn-group mx-1" role="group" aria-label="Basic example">
               <a href="utilisateurList.php" class="btn btn-outline-info active">Utilisateur</a>
               <a href="#" class="btn btn-outline-info">Alumni</a>
               <a href="#" class="btn btn-outline-info">Professeur</a>
               <a href="#" class="btn btn-outline-info">Partenaire</a>
               <a href="#" class="btn btn-outline-info">Étudiant</a>
          </div>
          <div class="btn-group mx-1" role="group" aria-label="Basic example">
               <a href="#" class="btn btn-outline-info">Formation</a>
               <a href="#" class="btn btn-outline-info">Offre</a>
               <a href="#" class="btn btn-outline-info">Évènement</a>
               <a href="#" class="btn btn-outline-info">Fiche entreprise</a>
               <a href="#" class="btn btn-outline-info">Postuler</a>
          </div>
          <div class="btn-group mx-1" role="group" aria-label="Basic example">
               <a href="#" class="btn btn-outline-info">Post</a>
               <a href="#" class="btn btn-outline-info">Réponse</a>
          </div>
     </div>
</nav>
<div class="container table-responsive">
     <table class="table table-striped table-hover align-middle">
          <thead class="table-light">
          <tr>
               <th scope="col">ID</th>
               <th scope="col">Nom</th>
               <th scope="col">Prénom</th>
               <th scope="col">Email</th>
               <th scope="col">Rôle</th>
               <th scope="col">Action</th>
          </tr>
          </thead>
          <tbody>
          <?php if (empty($utilisateurs)): ?>
               <tr><td colspan="6" class="text-center">Aucun utilisateur</td></tr>
          <?php else: ?>
               <?php foreach ($utilisateurs as $u): ?>
                    <tr>
                         <td><?= e($u['id_user'] ?? '') ?></td>
                         <td><?= e($u['nom'] ?? '') ?></td>
                         <td><?= e($u['prenom'] ?? '') ?></td>
                         <td><?= e($u['email'] ?? '') ?></td>
                         <td><?= e($u['role'] ?? '') ?></td>
                         <td>
                              <a href="utilisateurRead.php?id=<?= $u['id_user'] ?>" class="btn btn-info btn-sm">👁️</a>
                              <a href="utilisateurUpdate.php?id=<?= $u['id_user'] ?>" class="btn btn-warning btn-sm">✏️</a>
                              <a href="utilisateurDelete.php?id=<?= $u['id_user'] ?>" class="btn btn-danger btn-sm">🗑️</a>
                         </td>
                    </tr>
               <?php endforeach; ?>
          <?php endif; ?>
          </tbody>
     </table>
</div>
