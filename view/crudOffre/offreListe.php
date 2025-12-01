<?php
$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0] . '/public';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../src/modele/ModeleOffre.php';
require_once '../../src/bdd/config.php';
require_once '../../src/repository/OffreRepository.php';

$repo   = new OffreRepository();
$offres = $repo->getAllOffre();

$page = 'Offres';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ADMIN • LPRS</title>

    <!-- Bootstrap + icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">

    <!-- DataTables CSS -->
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <style>
        .banner {
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="../accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none align-items-center">
            <img src="https://giffiles.alphacoders.com/208/208817.gif"
                 class="rounded-circle mx-3"
                 style="max-width: 48px;"
                 alt="Logo LPRS">
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
            <a href="../account/accountRead.php" class="btn btn-outline-primary">Mon compte</a>
            <a href="../../src/treatment/traitementDeconnexion.php" class="btn btn-outline-danger">Déconnexion</a>
        <?php else: ?>
            <a href="../connexion.php" class="btn btn-outline-success">Connexion</a>
            <a href="../inscription.php" class="btn btn-outline-primary">Inscription</a>
        <?php endif; ?>
    </div>
</header>

<nav class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-center py-3 mb-4 border-bottom text-white bg-dark">
    <div class="nav col mb-2 justify-content-center mb-md-0">
        <div class="btn-group mx-1" role="group" aria-label="Navigation CRUD">
            <a href="../crudEntreprise/entrepriseRead.php" class="btn btn-outline-info">Entreprise</a>
            <a href="../crudEvenement/evenementListe.php" class="btn btn-outline-info">Évènement</a>
            <a href="../crudFormation/formationRead.php" class="btn btn-outline-info">Formation</a>
            <a href="../crudGestionnaire/gestionnaireRead.php" class="btn btn-outline-info">Gestionnaire</a>
            <a href="../crudOffre/offreListe.php" class="btn btn-outline-info active">Offre</a>
            <a href="../crudPartenaire/partenaireRead.php" class="btn btn-outline-info">Partenaire</a>
            <a href="../crudPost/postListe.php" class="btn btn-outline-info">Post</a>
            <a href="../crudReponse/reponseRead.php" class="btn btn-outline-info">Réponses</a>
            <a href="../crudUtilisateur/utilisateurRead.php" class="btn btn-outline-info">Utilisateur</a>
        </div>
    </div>
</nav>

<section class="container banner bg-info text-white text-center py-1 rounded border">
    <h1>Gestion <?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?></h1>
</section>

<section class="container text-center mb-3">
    <a href="offreCreate.php" class="btn btn-outline-success my-3 d-grid">
        Créer une offre
    </a>
</section>

<section class="container mb-5">
    <div class="table-responsive">
        <table id="offreTable" class="table table-striped table-hover align-middle">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Entreprise</th>
                <th>Type</th>
                <th>Salaire</th>
                <th>État</th>
                <th class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($offres) && is_array($offres)): ?>
                <?php foreach ($offres as $offre): ?>
                    <?php $idOffre = isset($offre['id_offre']) ? (int)$offre['id_offre'] : 0; ?>
                    <tr>
                        <td><?= htmlspecialchars($offre['id_offre']) ?></td>
                        <td><?= htmlspecialchars($offre['titre']) ?></td>
                        <td><?= htmlspecialchars($offre['nom_entreprise'] ?? '') ?></td>
                        <td><?= htmlspecialchars($offre['type']) ?></td>
                        <td><?= htmlspecialchars($offre['salaire']) ?></td>
                        <td><?= htmlspecialchars($offre['etat']) ?></td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <?php
                                $readUrl   = "offreRead.php?id={$idOffre}";
                                $updateUrl = "offreUpdate.php?id={$idOffre}";
                                ?>
                                <a href="<?= htmlspecialchars($readUrl, ENT_QUOTES, 'UTF-8') ?>"
                                   class="btn btn-info btn-sm" title="Voir les détails">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?= htmlspecialchars($updateUrl, ENT_QUOTES, 'UTF-8') ?>"
                                   class="btn btn-warning btn-sm" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="../../src/treatment/traitementDeleteOffre.php"
                                      method="post"
                                      style="display:inline-block;"
                                      onsubmit="return confirm('Voulez-vous vraiment supprimer cette offre ?');">
                                    <input type="hidden" name="id_offre"
                                           value="<?= htmlspecialchars($idOffre, ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="delete_offre" value="1">
                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<script>
    $(document).ready(function () {
        $('#offreTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            },
            responsive: true,
            order: [[1, 'asc']],
            columnDefs: [
                {orderable: false, targets: [6]}
            ]
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
