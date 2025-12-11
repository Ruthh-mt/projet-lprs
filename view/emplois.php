<?php
session_start();

$prefix = ''; // évite "Undefined variable $prefix" si tu l'utilises pour l'avatar

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
    $lesOffres = [];
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

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- jQuery + DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <style>
        .table-wrap { padding: 0 20px 30px; }
        .row-actions { white-space: nowrap; }
    </style>
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
    <div class="table-wrap">
        <table class="table" id="offre-table">
            <thead>
            <tr>
                <th>Titre</th>
                <th>Description</th>
                <th>Mission</th>
                <th>Salaire</th>
                <th>Entreprise</th>
                <th>Type d'offre</th>
                <th>Etat</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($lesOffres as $offre): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($offre['titre']) ?></strong></td>
                    <td><?= htmlspecialchars($offre['description']) ?></td>
                    <td><?= htmlspecialchars($offre['mission']) ?></td>
                    <td><?= htmlspecialchars($offre['salaire']) ?></td>
                    <td><?= htmlspecialchars($offre['nom_entreprise']) ?></td>
                    <td><?= htmlspecialchars($offre['type']) ?></td>
                    <td><?= htmlspecialchars($offre['etat']) ?></td>


                    <td class="row-actions">

                        <?php if (
                                $_SESSION['utilisateur']['role'] === 'Partenaire'
                        ): ?>

                            <a href="crudOffre/offreUpdate.php?id=<?= $offre['id_offre'] ?>"
                               class="btn btn-sm btn-outline-primary me-1" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="../src/treatment/traitementDeleteOffre.php"
                                  method="post" class="d-inline">
                                <input type="hidden" name="id_offre" value="<?= (int)$offre['id_offre'] ?>">
                                <input type="hidden" name="delete_offre" value="1">
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        title="Supprimer"
                                        onclick="return confirm('Supprimer cette offre ?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                        <?php endif; ?>

                        <?php if ($_SESSION['utilisateur']['role'] === 'Etudiant' || $_SESSION['utilisateur']['role'] === 'Alumni'): ?>
                            <form action="../view/postuler.php?id=<?= (int)$offre['id_offre'] ?>"
                                  method="post" class="d-inline">
                                <input type="hidden" name="id_offre" value="<?= (int)$offre['id_offre'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-success" title="Postuler à cette offre">
                                    <i class="bi bi-send-fill"></i> Postuler
                                </button>
                            </form>
                        <?php endif; ?>

                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>


<script>
    $(document).ready(function () {
        $('#offre-table').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json",
                emptyTable: "Aucune offre disponible pour l'instant"
            },
            pageLength: 10,
            ordering: true,
            searching: true,
            responsive: true
        });
    });

    function redirection() {
        window.location.href = 'connexion.php';
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
