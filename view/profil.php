<?php
session_start();

$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0] . '/public';

require_once "../src/repository/PostulerRepository.php";
require_once "../src/repository/PartenaireRepository.php";
require_once "../src/repository/OffreRepository.php";
require_once "../src/repository/AlumniRepository.php";

// Repositories
$postulerRepository = new PostulerRepository();
$partenaireRep      = new PartenaireRepository();
$offreRep           = new OffreRepository();
$alumniRep          = new AlumniRepository();

$role   = $_SESSION['utilisateur']['role'] ?? null;
$idUser = $_SESSION['utilisateur']['id_user'] ?? null;

// Pagination
$perPage    = 3; // nombre de cartes par page
$page       = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$total      = 0;
$totalPages = 1;
$itemsPage  = [];
$a_un_items   = false;

// Récupération et découpage selon le rôle
if ($role === 'Etudiant' && $idUser || $role === 'Alumni' && $idUser) {

    $candidaturesEtudiant = $postulerRepository->findCandidatures($idUser);
    $total = count($candidaturesEtudiant);

    if ($total > 0) {
        $totalPages = max(1, (int)ceil($total / $perPage));
        if ($page > $totalPages) $page = $totalPages;
        $start     = ($page - 1) * $perPage;
        $itemsPage = array_slice($candidaturesEtudiant, $start, $perPage);
        $a_un_items = true;
    }

} elseif ($role === 'Partenaire' && $idUser) {

    $offresPartenaire = $offreRep->getOffresParenaire($idUser);
    $total = count($offresPartenaire);

    if ($total > 0) {
        $totalPages = max(1, (int)ceil($total / $perPage));
        if ($page > $totalPages) $page = $totalPages;
        $start     = ($page - 1) * $perPage;
        $itemsPage = array_slice($offresPartenaire, $start, $perPage);
        $a_un_items  = true;
    }
    
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
<body class="bg-dark text-white">
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://giffiles.alphacoders.com/208/208817.gif" class="rounded-circle mx-3" style="max-width: 48px;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
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
                        <img src="<?= $prefix . htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil" class="rounded-circle" style="max-width: 48px;object-fit:cover;">
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

<section class="bg-dark text-white text-center py-1 rounded">
    <div>
        <?php
        if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] == "Etudiant") {
            echo "<h1>Mes candidatures</h1>";
        } elseif (isset($_SESSION['utilisateur']) &&
                ($_SESSION['utilisateur']['role'] == "Partenaire" || $_SESSION['utilisateur']['role'] == "Alumni")) {
            echo "<h1>Mes offres</h1>";
        }
        ?>
        <button type="button" class="btn btn-outline-light" onclick="window.location.href='emplois.php'">
            <i class="bi bi-arrow-left-circle"></i> Retour
        </button>

        <section class="container my-4">
            <?php
            if (!isset($_SESSION['utilisateur'])) {
                echo '<h5 class="alert alert-danger alert-dismissible fade show"> Vous n\'êtes pas connecté. Veuillez vous connecter.</h5>';
            }
            elseif ($role === 'Etudiant' || $role === 'Alumni') {

                echo '<div class="d-flex flex-wrap justify-content-start gap-4">';

                if ($a_un_items) {
                    foreach ($itemsPage as $candidature) {
                        echo '<div class="card shadow-sm" style="width: 320px; height: 430px; flex: 0 0 auto;">
                            <img src="https://wallpapers.com/images/hd/4k-vector-snowy-landscape-p7u7m7qyxich2h31.jpg"
                                 class="card-img-top"
                                 alt="Image événement"
                                 style="height: 180px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold">' . htmlspecialchars($candidature['titre']) . '</h5>
                                <p class="card-text flex-grow-1 text-muted">
                                    ' . htmlspecialchars(substr($candidature['description'], 0, 100)) . '...
                                </p>
                                <a href="crudPostuler/afficheCandidatures.php?id=' . $candidature['id_offre'] . '"
                                   class="btn btn-primary mt-auto">
                                    En savoir plus
                                </a>
                            </div>
                            <div class="card-footer text-muted small">
                                Dernière mise à jour : ' . date("d/m/Y H:i") . '
                            </div>
                        </div>';
                    }
                } else {
                    echo "<h5> Il semblerait qu'il n'y a pas de candidatures</h5>
                          <br>
                          <p>Soyez le/la premier/e à postuler </p>";
                }
                echo '</div>';

            }
            elseif ($role === 'Partenaire') {

                echo '<div class="d-flex flex-wrap justify-content-start gap-4">';

                if ($a_un_items) {
                    foreach ($itemsPage as $offre) {
                        echo '<div class="card shadow-sm" style="width: 320px; height: 430px; flex: 0 0 auto;">
                            <img src="https://wallpapers.com/images/hd/4k-vector-snowy-landscape-p7u7m7qyxich2h31.jpg"
                                 class="card-img-top"
                                 alt="Image événement"
                                 style="height: 180px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold">' . htmlspecialchars($offre['titre']) . '</h5>
                                <p class="card-text flex-grow-1 text-muted">
                                    ' . htmlspecialchars(substr($offre['description'], 0, 100)) . '...
                                </p>
                                <a href="crudOffre/offreRead.php?id=' . $offre['id_offre'] . '"
                                   class="btn btn-primary mt-auto">
                                    En savoir plus
                                </a>
                            </div>
                            <div class="card-footer text-muted small">
                                Dernière mise à jour : ' . date("d/m/Y H:i") . '
                            </div>
                        </div>';
                    }

                } else {
                    echo "<h5> Il semblerait qu'il n'y a pas d'offres</h5>
                          <br>
                          <p>Soyez le/la premier/e à poster une offre </p>";
                }
                echo '</div>';

            }
            ?>
        </section>
    </div>
</section>

<?php if ($a_un_items && $totalPages > 1): ?>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center mt-3 mb-4">
            <!-- Précédent -->
            <li class="page-item <?= ($page <= 1 ? 'disabled' : '') ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <!-- Numéros -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page ? 'active' : '') ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <!-- Suivant -->
            <li class="page-item <?= ($page >= $totalPages ? 'disabled' : '') ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
