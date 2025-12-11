<?php
$prefix ='../public';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$prefix = '';

if (isset($_SESSION['utilisateur'])) {
    require_once('../src/bdd/config.php');
    require_once('../src/repository/OffreRepository.php');
    require_once('../src/repository/PartenaireRepository.php');
    require_once('../src/repository/AlumniRepository.php');

    $offreRep = new OffreRepository();
    $lesOffres = $offreRep->getAllOffre();

    $partenaireRep = new PartenaireRepository();
    $partenaire_a_une_fiche = $partenaireRep->getFicheByPartenaire($_SESSION['utilisateur']['id_user']);

    $alumniRep = new AlumniRepository();
    $id_user = $_SESSION['utilisateur']['id_user'];
} else {
    $partenaire_a_une_fiche = null;
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EMPLOIS • LPRS</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <link rel="stylesheet" href="../src/assets/emplois.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body>

<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://giffiles.alphacoders.com/208/208817.gif" class="rounded-circle mx-3" style="max-width: 48px;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>

    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="accueil.php" class="btn btn-outline-light me-2">Accueil</a></li>
        <li class="nav-item"><a href="evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="emplois.php" class="btn btn-outline-light active me-2">Emplois</a></li>
        <li class="nav-item"><a href="forum.php" class="btn btn-outline-light me-2">Forum</a></li>

        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Gestionnaire'): ?>
            <li class="nav-item">
                <a href="administration.php" class="btn btn-outline-warning me-2">Administration</a>
            </li>
        <?php endif; ?>
    </ul>

    <div class="col-2 text-end me-3">
        <div class="dropdown">
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <?php $avatar = $_SESSION['utilisateur']['avatar'] ?? null; ?>
                <a href="#" class="d-inline-block text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <?php if ($avatar): ?>
                        <img src="<?= $prefix . htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>"
                             alt="Photo de profil" class="rounded-circle"
                             style="max-width: 48px;object-fit:cover;">
                    <?php else: ?>
                        <i class="bi bi-person-circle fs-3 text-light"></i>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li><a class="dropdown-item text-primary" href="account/accountRead.php"><i class="bi bi-person"></i> Mon compte</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../src/treatment/traitementDeconnexion.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
                </ul>
            <?php else: ?>
                <a href="#" class="d-inline-block text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-3 text-light"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li><a class="dropdown-item" href="connexion.php"><i class="bi bi-box-arrow-in-right"></i> Connexion</a></li>
                    <li><a class="dropdown-item" href="inscription.php"><i class="bi bi-person-plus"></i> Inscription</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</header>

<section class="creation-offre">
    <div class="card">
        <div class="card-head d-flex justify-content-between align-items-center px-3 py-3 border-bottom">
            <h2 class="m-0">Offres d'emploi</h2>

            <div class="d-flex gap-2">
                <?php if (!empty($_SESSION) && $_SESSION['utilisateur']['role'] === 'Partenaire'): ?>
                    <a href="profil.php" class="btn btn-dark">Voir mes offres</a>
                    <a href="crudOffre/offreCreate.php" class="btn btn-dark">Créer une offre</a>

                <?php elseif (!empty($_SESSION) && $_SESSION['utilisateur']['role'] === 'Etudiant'
                    || $_SESSION['utilisateur']['role'] === 'Alumni') : ?>
                    <a href="profil.php" class="btn btn-dark">
                        <i class="bi bi-plus-circle"></i> Mes candidatures
                    </a>

                <?php else: ?>
                    <button class="btn btn-dark" onclick="redirection()">Veuillez vous connecter</button>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

<?php if (isset($_SESSION['utilisateur'])): ?>

    <div class="container mt-4">
        <div class="row g-3">

            <?php foreach ($lesOffres as $offre): ?>

                <div class="col-md-4">
                    <div class="offre-card">

                        <!-- TITRE -->
                        <div class="offre-title">
                            <?= htmlspecialchars($offre->titre) ?>
                        </div>

                        <div class="offre-entreprise">
                            <?= htmlspecialchars($offre->nom_entreprise) ?>
                        </div>
                        <!-- DESCRIPTION -->
                        <div class="offre-desc">
                            <?= htmlspecialchars(substr($offre->description ?? "Aucune description disponible", 0, 150)) ?>...
                        </div>

                        <!-- ACTIONS -->
                        <div class="offre-actions d-flex gap-2">

                            <?php if ($_SESSION['utilisateur']['role'] === 'Etudiant'
                                    || $_SESSION['utilisateur']['role'] === 'Alumni'): ?>

                                <a href="enSavoirPlusOffre.php?id=<?= $offre->id_offre ?>" class="btn btn-primary">
                                    Voir l'offre
                                </a>

                            <?php elseif ($_SESSION['utilisateur']['role'] === 'Partenaire' && $partenaire_a_une_fiche->id_fiche_entreprise == $offre->ref_fiche) : ?>

                                <a href="crudOffre/offreUpdate.php?id=<?= $offre->id_offre ?>" class="btn btn-secondary">
                                    Modifier
                                </a>

                                <a href="crudOffre/offreDelete.php?id=<?= $offre->id_offre ?>"
                                   class="btn btn-danger"
                                   onclick="return confirm('Voulez-vous vraiment supprimer cette offre ?')">
                                    Supprimer
                                </a>

                            <?php endif; ?>

                        </div>

                    </div>
                </div>

            <?php endforeach; ?>

        </div>
    </div>

<?php endif; ?>

</body>


</html>
