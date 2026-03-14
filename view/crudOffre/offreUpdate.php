<?php
session_start();
require_once("../../src/bdd/config.php");
require_once("../../src/repository/OffreRepository.php");

$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0].'/public';
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('Aucune offre sélectionné'); window.location.href='../evenements.php';</script>";
    exit;
}
$offreRepo = new OffreRepository();
$offre = $offreRepo->getOffreById($id);


?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Création d’une offre • LPRS</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>

<body>

<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="../accueil.php" class="d-inline-flex text-decoration-none align-items-center">
            <img src="https://media.tenor.com/1DV7nkfj5OkAAAAM/blobgodeto-blobdance.gif"
                 class="rounded-circle mx-3"
                 style="max-width: 15%; height: auto;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>

    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="../accueil.php" class="btn btn-outline-light me-2">Accueil</a></li>
        <li class="nav-item"><a href="../evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="../emplois.php" class="btn btn-outline-light active me-2">Emplois</a></li>
        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Gestionnaire'): ?>
            <li class="nav-item">
                <a href="../administration.php" class="btn btn-outline-warning me-2">Administration</a>
            </li>
        <?php endif; ?>
    </ul>

    <div class="col-2 btn-group md-3 me-3 text-end">
        <?php if (isset($_SESSION['utilisateur'])): ?>
            <a href="../account/accountRead.php" class="btn btn-outline-primary">Mon compte</a>
            <a href="../../src/treatment/traitementDeconnexion.php" class="btn btn-outline-danger">Déconnexion</a>
        <?php else: ?>
            <a href="../connexion.php" class="btn btn-outline-success">Connexion</a>
            <a href="../inscription.php" class="btn btn-outline-primary">Inscription</a>
        <?php endif; ?>
    </div>
</header>



<!-- SECTION FORMULAIRE -->
<div class="container mb-5">
    <div class="section-offre">

        <!-- Bandeau titre -->
        <div class="offre-header d-flex justify-content-between align-items-center">
            <h2 class="fw-bold">Modifier l'offre (Reference numero: <?= $_GET['id']?>)</h2>
            <button type="button" class="btn btn-outline-light" onclick="window.location.href='../emplois.php'">
                <i class="bi bi-arrow-left-circle"></i> Retour
            </button>

        </div>

        <!-- Formulaire -->
        <form class="mt-4" action="../../src/treatment/traitementUpdateOffre.php" method="post">

            <input type="hidden" value="<?= $_GET['id']?>" name="id_offre">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="titre" class="form-label">Titre du poste</label>
                <input class="form-control" type ="text" name="titre" value="<?= $offre->titre ?>">
            </div>


            <div class="mb-3">
                <label for="description" class="form-label">Description du contrat</label>
                <textarea class="form-control"  name="description" ><?= htmlspecialchars($offre->description) ?></textarea>

            </div>

            <div class="mb-3">
                <label for="mission" class="form-label">Mission</label>
                <textarea class="form-control"  name="mission" ><?= $offre->mission ?></textarea>

            </div>
            <div class="mb-3">
                <label for="type_contrat" class="form-label">Type de contrat</label>
                <select class="form-select" name="type_contrat">
                    <option value="<?= $offre->type?>" selected><?= $offre->type ?></option>
                    <option value="cdi">CDI</option>
                    <option value="cdd">CDD</option>
                    <option value="alternance">Alternance</option>
                    <option value="contrat de professionnalisation">Contrat de professionnalisation</option>

                </select>
            </div>

            <div class="mb-3">
                <label for="salaire" class="form-label">Salaire</label>
                <input class="form-control" type="number" id="salaire" name="salaire" value="<?= $offre->salaire?>">
            </div>

            <div class="mb-3">
                <label for="ref_fiche" class="form-label">Entreprise</label>
                <input class="form-control" value ="<?= $offre->ref_fiche?>" name="ref_fiche" >
            </div>

            <div class="mb-3">
                <label for="etat" class="form-label">Type offre</label>
                <select class="form-select" name="type_contrat">
                    <option value="<?= $offre->etat?>" selected><?= $offre->etat ?></option>
                    <option value="CDD">CDD</option>
                    <option value="CDI">CDI</option>
                    <option value="Alternance">Alternance</option>
                    <option value="Stage">Stage</option>
                    <option value="Interim">Interim</option>


                </select>
            </div>
            <div class="mb-3">
                <label for="etat" class="form-label">Etat</label>
                <select class="form-select" name="etat">
                    <option value="<?= $offre->etat?>" selected><?= $offre->etat ?></option>
                    <option value="Publiée">Publiée</option>
                    <option value="Archivée">Archivée</option>
                    <option value="Brouillon">Brouillon</option>
                    <option value="En attente">En attente</option>

                </select>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit">Modifier</button>
                <button class="btn btn-secondary" type="reset">Annuler les modifications</button>
                <a href="../emplois.php" class="btn btn-outline-dark">Retour</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
