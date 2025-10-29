<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['utilisateur'])) {
     header('Location: ../connexion.php');
     exit();
}
$u = $_SESSION['utilisateur'];

function e(?string $v): string
{
     return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

?>
<!doctype html>
<html lang="fr">
<head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>LPRS</title>
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
           integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
           crossorigin="anonymous">
</head>
<body>
<header
     class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
     <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
          <a href="../accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
               <img src="https://media.tenor.com/ax43-bYnblUAAAAj/beebobble.gif"
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
               <a href="accountRead.php" class="btn btn-outline-primary active">Mon compte</a>
               <a href="../../src/treatment/traitementDeconnexion.php" class="btn btn-outline-danger">Déconnexion</a>
          <?php else: ?>
               <a href="../connexion.php" class="btn btn-outline-success">Connexion</a>
               <a href="../inscription.php" class="btn btn-outline-primary">Inscription</a>
          <?php endif; ?>
     </div>
</header>
<section class="container banner text-center bg-dark text-white text-center py-3 rounded">
     <h1>Mon compte</h1>
</section>
<div class="container rounded my-3">
     <?php if (!empty($_SESSION['success'])): ?>
          <div class="alert alert-success"><?= e($_SESSION['success']);
               unset($_SESSION['success']); ?></div>
     <?php endif; ?>
     <?php if (!empty($_SESSION['error'])): ?>
          <div class="alert alert-danger"><?= e($_SESSION['error']);
               unset($_SESSION['error']); ?></div>
     <?php endif; ?>
     <div class="row justify-content-center>
          <div class=" mx-auto
     ">
     <div class="card shadow-sm">
          <div class="card-body">
               <h5 class="card-title text-center mb-4">Informations personnelles</h5>
               <form action="../../src/treatment/traitementAccountUpdate.php" method="post"
                     enctype="multipart/form-data" novalidate>
                    <div class="row g-3">
                         <div class="col mx-3 form-floating mb-3">
                              <input type="prenom" class="form-control" id="floatingInput" placeholder="Prénom"
                                     name="prenom" value="<?= e($u['prenom'] ?? '') ?>" required>
                              <label for="floatingInput">Prénom</label>
                         </div>
                         <div class="col mx-3 form-floating mb-3">
                              <input type="text" class="form-control" id="nom" placeholder="Nom" name="nom" value="<?= e($u['nom'] ?? '') ?>" required >
                              <label for="nom">Nom</label>
                         </div>
                    </div>
                    <div class="row g-3">
                         <div class="col mx-3 form-floating mb-3">
                              <input type="email" class="form-control" id="floatingInput" placeholder="Adresse email"
                                     name="email" value="<?= e($u['email'] ?? '') ?>" required disabled>
                              <label for="floatingInput">Adresse email</label>
                         </div>
                         <div class="col mx-3 form-floating mb-3">
                              <input type="text" class="form-control" id="floatingInput" placeholder="Rôle" name="role"
                                     value="<?= e($u['role'] ?? '') ?>" required disabled>
                              <label for="floatingInput">Rôle</label>
                         </div>
                    </div>
                    <div class="row g-3">
                         <div class="col mx-3 form-floating mb-3">
                              <input type="password" class="form-control" id="floatingInput"
                                     placeholder="Changement de mot de passe" name="password">
                              <label for="floatingInput">Changement de mot de passe</label>
                         </div>
                         <div class="col mx-3 form-floating mb-3">
                              <input type="password" class="form-control" id="floatingInput"
                                     placeholder="Confirmation mot de passe" name="confirmpassword">
                              <label for="floatingInput">Confirmation mot de passe</label>
                         </div>
                    </div>
                    <hr class="my-4">
                    <?php $role = $u['role'] ?? ''; ?>
                    <h5 class="card-title text-center mb-4">Informations additionnelles</h5>

                    <?php if ($role === 'Étudiant' || $role === 'Alumni'): ?>
                         <div class="row g-3">
                              <div class="col">
                                   <label for="annee_promo" class="form-label">Année de promo</label>
                                   <input type="number" class="form-control" id="annee_promo" name="annee_promo"
                                          value="<?= e($u['annee_promo'] ?? '') ?>" min="1900" max="2100">
                              </div>
                              <div class="col">
                                   <label for="cv" class="form-label">CV</label>
                                   <input class="form-control" type="file" id="cv" name="cv" accept=".pdf">
                                   <?php if (!empty($u['cv'])): ?>
                                        <div class="form-text">CV actuel : <a href="<?= e($u['cv']) ?>" target="_blank">Voir</a></div>
                                   <?php endif; ?>
                              </div>
                         </div>
                         <div class="row g-3">
                              <?php if ($role === 'Étudiant'): ?>
                                   <div class="col">
                                        <label for="ref_formation" class="form-label">Formation</label>
                                        <input type="text" class="form-control" id="ref_formation" name="ref_formation"
                                               value="<?= e($u['ref_formation'] ?? '') ?>">
                                   </div>
                              <?php elseif ($role === 'Alumni'): ?>
                                   <div class="col">
                                        <label for="poste" class="form-label">Poste</label>
                                        <input type="text" class="form-control" id="poste" name="poste"
                                               value="<?= e($u['poste'] ?? '') ?>">
                                   </div>
                                   <div class="col-md-6">
                                        <label for="ref_fiche_entreprise" class="form-label">Entreprise</label>
                                        <input type="text" class="form-control" id="ref_fiche_entreprise"
                                               name="ref_fiche_entreprise"
                                               value="<?= e($u['ref_fiche_entreprise'] ?? '') ?>">
                                   </div>
                              <?php endif; ?>
                         </div>
                    <?php elseif ($role === 'Professeur'): ?>
                         <div class="row g-3">
                              <div class="col">
                                   <label for="specialite" class="form-label">Spécialité</label>
                                   <input type="text" class="form-control" id="specialite" name="specialite"
                                          value="<?= e($u['specialite'] ?? '') ?>">
                              </div>
                         </div>
                    <?php elseif ($role === 'Partenaire'): ?>
                         <div class="row g-3">
                              <div class="col">
                                   <label for="poste" class="form-label">Poste</label>
                                   <input type="text" class="form-control" id="poste" name="poste"
                                          value="<?= e($u['poste'] ?? '') ?>">
                              </div>
                              <div class="col">
                                   <label for="ref_fiche_entreprise" class="form-label">Entreprise</label>
                                   <input type="text" class="form-control" id="ref_fiche_entreprise"
                                          name="ref_fiche_entreprise"
                                          value="<?= e($u['ref_fiche_entreprise'] ?? '') ?>">
                              </div>
                              <div class="col">
                                   <label for="cv" class="form-label">CV</label>
                                   <input class="form-control" type="file" id="cv" name="cv" accept=".pdf">
                                   <?php if (!empty($u['cv'])): ?>
                                        <div class="form-text">CV actuel : <a href="<?= e($u['cv']) ?>" target="_blank"
                                                                              rel="noopener">Voir</a></div>
                                   <?php endif; ?>
                              </div>
                         </div>
                    <?php endif; ?>
                    <div class="d-flex gap-2 justify-content-end mt-4">
                         <a href="accountRead.php" class="btn btn-outline-secondary">Annuler</a>
                         <button type="submit" class="btn btn-outline-success">Enregistrer</button>
                    </div>
               </form>
          </div>
     </div>
          <a href="accountDelete.php" class="btn btn-outline-danger my-3">Supprimer mon compte</a>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoIIfJcLyjU29tka7Sk3YSA8l7IgGKmFckcImFV8Qbsw3"
        crossorigin="anonymous"></script>
</body>
</html>
