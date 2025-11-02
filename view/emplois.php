<?php
session_start();
require_once ('../src/bdd/config.php');
require_once '../src/repository/OffreRepository.php';
require_once '../src/repository/OffreRepository.php';
$pdo  = (new Config())->connexion();
$offreRepository = new OffreRepository() ;
$allOffres = $offreRepository->getAllOffres();
?>
<!doctype html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ACCUEIL • LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</head>
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
        <li class="nav-item"><a href="accueil.php" class="btn btn-outline-light active dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="" class="btn btn-outline-light me-2">Emplois</a></li>
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
            <a href="connexion.php" class="btn btn-outline-success">Connexion</a>
            <a href="inscription.php" class="btn btn-outline-primary">Inscription</a>
        <?php endif; ?>
    </div>
</header>

<section class="creation-offre">
    <div class="card">
        <div class="card-head d-flex justify-content-between align-items-center px-3 py-3 border-bottom">
            <h2 class="m-0">Offres d'emploi</h2>
            <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Partenaire'): ?>
            <a href="crudPartenaire/mesOffres.php" class="btn btn-outline-dark">Mes offres</a>
            <?php endif; ?>
            <a href="crudOffre/offreCreate.php" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle"></i> Créer une offre
            </a>
        </div>
    <div class="table-wrap">
            <table class="table" id="offre-table">
                <thead>
                <tr><th>Titre</th><th>Description</th><th>Mission</th><th>Salaire</th><th>Entreprise</th><th>Type d'offre</th><th>Etat</th><th>Actions</th></tr>
                </thead>
                <tbody>
                <?php foreach ($allOffres as  $offre): ?>
                <tr>
                <td><strong><?= htmlspecialchars($offre['titre']) ?></strong></td>
                <td><?= htmlspecialchars($offre['description']) ?></td>
                <td><?= htmlspecialchars($offre['mission']) ?></td>
                <td><?= htmlspecialchars($offre['salaire']) ?></td>
                <td><?= htmlspecialchars($offre['nom_entreprise']) ?></td>
                <td><?= htmlspecialchars($offre['type']) ?></td>
                <td><?= htmlspecialchars($offre['etat']) ?></td>
                    <td class="row-actions">
                        <a href="crudOffre/offreUpdate.php?id=<?= $offre['id_offre'] ?>"
                           class="btn btn-sm btn-outline" title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form action="../view/crudOffre/deleteOffre.php" method="post" style="display:inline;">
                            <input type="hidden" name="id_offre" value="<?= htmlspecialchars($offre['id_offre']) ?>">
                            <input type="hidden" name="delete_offre" value="1">
                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                    title="Supprimer" onclick="return confirm('Supprimer cette offre ?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        <form action="../view/postuler.php?id=<?= $offre['id_offre'] ?>" method="post" style="display:inline;">
                            <input type="hidden" name="id_offre" value="<?= htmlspecialchars($offre['id_offre']) ?>">
                            <button type="submit" class="btn btn-sm btn-outline-success" title="Postuler à cette offre">
                                <i class="bi bi-send-fill"></i> Postuler
                            </button>
                        </form>
                    </td>
                 <?php endforeach; ?>
                </tr>
        </form>
     </tbody>
        </table>
    </div>
</div>
</section>

<!-- Datatable JS id="offre-table" -->
<script>
    $(document).ready(function () {
        $('#offre-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
            },
            "pageLength": 10,  // nombre de lignes par page
            "ordering": true,  // tri des colonnes activé
            "searching": true, // barre de recherche activée
            "responsive": true // design responsive
        });
    });
</script>




