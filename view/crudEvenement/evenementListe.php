<?php
$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0].'/public';
if (session_status() === PHP_SESSION_NONE) {
    session_start();

    require_once '../../src/bdd/config.php';
    require_once '../../src/repository/EvenementRepository.php';

    $evenementRepo = new EvenementRepository();
    $page = 'Évènement';

    // Récupération de la page actuelle depuis l'URL, 1 par défaut
    $pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $parPage = 10; // Nombre d'éléments par page
    $totalEvenements = $evenementRepo->countAllEvenement();
    $pagesTotales = ceil($totalEvenements / $parPage);

    // Vérification que la page demandée est valide
    if ($pageActuelle < 1) {
        $pageActuelle = 1;
    } elseif ($pageActuelle > $pagesTotales && $pagesTotales > 0) {
        $pageActuelle = $pagesTotales;
    }

    $offset = ($pageActuelle - 1) * $parPage;
    $evenements = $evenementRepo->getAllEvenement($offset, $parPage);
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ADMIN • LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-1.13.8/datatables.min.css" rel="stylesheet">
    <style>
        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin: 20px auto;
        }
        .dataTables_wrapper .dataTables_filter input {
            margin-left: 10px;
            border-radius: 4px;
            border: 1px solid #ced4da;
            padding: 4px 8px;
        }
        .dataTables_wrapper .dataTables_length select {
            border-radius: 4px;
            border: 1px solid #ced4da;
            padding: 4px 8px;
        }
    </style>
</head>
<body>
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="../accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://i.pinimg.com/originals/a0/50/1e/a0501e0c5659dcfde397299e4234e75a.gif" class="mx-3" style="max-width: 48px;">
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
                <a href="../administration.php" class="btn btn-outline-warning active me-2">Administration</a>
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
                        <img src="<?= $prefix.htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil" class="rounded-circle" style="max-width: 48px;object-fit:cover;">
                    <?php else: ?>
                        <i class="bi bi-person-circle fs-3 text-light"></i>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li><a class="dropdown-item text-primary" href="../account/accountRead.php"><i class="bi bi-person"></i> Mon compte</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../../src/treatment/traitementDeconnexion.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
                </ul>
            <?php else: ?>
                <a href="#" class="d-inline-block text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-3 text-light"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li><a class="dropdown-item text-primary" href="../connexion.php"><i class="bi bi-box-arrow-in-right"></i> Connexion</a></li>
                    <li><a class="dropdown-item text-success" href="../inscription.php"><i class="bi bi-person-plus"></i> Inscription</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</header>
<nav class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom text-white bg-dark">
    <div class="nav col mb-2 justify-content-center mb-md-0">
        <div class="btn-group mx-1" role="group" aria-label="Basic example">
            <a href="../crudEntreprise/entrepriseRead.php" class="btn btn-outline-info">Entreprise</a>
            <a href="../crudEvenement/evenementListe.php" class="btn btn-outline-info active">Évènement</a>
            <a href="../crudFormation/formationRead.php" class="btn btn-outline-info">Formation</a>
            <a href="../crudGestionnaire/gestionnaireRead.php" class="btn btn-outline-info">Gestionnaire</a>
            <a href="../crudOffre/offreListe.php" class="btn btn-outline-info">Offre</a>
            <a href="../crudPartenaire/partenaireRead.php" class="btn btn-outline-info">Partenaire</a>
            <a href="../crudPost/postListe.php" class="btn btn-outline-info">Post</a>
            <a href="../crudReponse/reponseRead.php" class="btn btn-outline-info">Réponses</a>
            <a href="../crudUtilisateur/utilisateurRead.php" class="btn btn-outline-info">Utilisateur</a>
        </div>
        <a href="../crudUtilisateur/utilisateurAValider.php" class="btn btn-outline-warning">A valider</a>
    </div>
</nav>
<section class="container banner bg-info text-white text-center py-1 rounded border">
    <h1>Gestion <?=$page?></h1>
</section>
<section class="container text-center">
    <a href="evenementCreate.php" class="btn btn-outline-success my-3 d-grid">Créer un évènement</a>
</section>

<section class="container">
    <div class="table-container">
        <table id="evenementsTable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Lieu</th>
                    <th>Places</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($evenements as $evenement): ?>
                    <tr>
                        <td><?= htmlspecialchars($evenement->id_evenement) ?></td>
                        <td><?= htmlspecialchars($evenement->titre_eve) ?></td>
                        <td><?= htmlspecialchars($evenement->type_eve) ?></td>
                        <td><?= htmlspecialchars($evenement->lieu_eve) ?></td>
                        <td><?= htmlspecialchars($evenement->nb_place) ?></td>
                        <td>
                            <?php if ($evenement->est_valide == 1): ?>
                                <span class="badge bg-success">Validé</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">En attente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="evenementUpdate.php?id=<?= $evenement->id_evenement ?>" class="btn btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="../../src/treatment/traitementDeleteEvenement.php?id=<?= $evenement->id_evenement ?>" class="btn btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Pagination -->
        <?php if ($pagesTotales > 1): ?>
            <nav aria-label="Navigation des pages">
                <ul class="pagination justify-content-center">
                    <?php if ($pageActuelle > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $pageActuelle - 1 ?>" aria-label="Précédent">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $pagesTotales; $i++): ?>
                        <li class="page-item <?= ($i == $pageActuelle) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($pageActuelle < $pagesTotales): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $pageActuelle + 1 ?>" aria-label="Suivant">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#evenementsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json',
                search: "Rechercher :",
                lengthMenu: "Afficher _MENU_ éléments par page",
                info: "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                paginate: {
                    first: "Premier",
                    last: "Dernier",
                    next: "Suivant",
                    previous: "Précédent"
                }
            },
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [6] } // Désactive le tri sur la colonne des actions
            ],
            order: [[0, 'desc']] // Tri par ID décroissant par défaut
        });
    });
</script>
</body>
</html>