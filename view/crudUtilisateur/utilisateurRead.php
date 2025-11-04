<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../src/modele/ModeleUtilisateur.php';
require_once '../../src/bdd/config.php';
require_once '../../src/repository/UtilisateurRepository.php';

$repo = new UtilisateurRepository();
$utilisateurs = $repo->findAll();

$page = 'Utilisateur';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ACCUEIL • LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</head>
<body>
<header
        class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://media.tenor.com/ifEkV-aGn3EAAAAi/fat-cat.gif"
                 class="rounded-circle mx-3"
                 style="max-width: 15%; height: auto;">
            <div class="fs-4 text-light text-uppercase">LPRS • ADMIN</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="../../accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="../../evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="../../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="../../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="../../emplois.php" class="btn btn-outline-light me-2">Emplois</a></li>
        <li class="nav-item"><a href="../../forum.php" class="btn btn-outline-light me-2">Forum</a></li>
        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Gestionnaire'): ?>
            <li class="nav-item">
                <a href="../../administration.php" class="btn btn-outline-warning active me-2">Administration</a>
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
            <a href="../crudPostuler/candidatureRead.php" class="btn btn-outline-danger">Candidature</a>
            <a href="../crudEntreprise/entrepriseRead.php" class="btn btn-outline-info">Entreprise</a>
            <a href="../crudEvenement/evenementRead.php" class="btn btn-outline-danger">Évènement</a>
            <a href="../crudFormation/formationRead.php" class="btn btn-outline-info">Formation</a>
            <a href="../crudGestionnaire/gestionnaireRead.php" class="btn btn-outline-info">Gestionnaire</a>
            <a href="../crudOffre/offreRead.php" class="btn btn-outline-info">Offre</a>
            <a href="../crudPartenaire/partenaireRead.php" class="btn btn-outline-info">Partenaire</a>
            <a href="../crudPost/postRead.php" class="btn btn-outline-danger">Post</a>
            <a href="../crudReponse/reponseRead.php" class="btn btn-outline-info">Réponses</a>
            <a href="utilisateurRead.php" class="btn btn-outline-info active">Utilisateur</a>
        </div>
    </div>
</nav>

<section class="container bg-info text-white text-center py-1 rounded border">
    <h1>Gestion <?= htmlspecialchars($page) ?></h1>
</section>

<section class="container text-center">
    <a href="utilisateurCreate.php" class="btn btn-outline-success my-3 d-grid">Ajouter un utilisateur</a>
</section>

<section class="container">
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
        <tr>
            <th>ID</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Validateur</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($utilisateurs) && is_array($utilisateurs)): ?>
            <?php foreach ($utilisateurs as $utilisateur): ?>
                <tr>
                    <td><?= htmlspecialchars($utilisateur['id_user']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['prenom']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['nom']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['email']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['role']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['ref_validateur'] ?? '') ?></td>
                    <td class="text-center">
                        <?php
                        $id = isset($utilisateur['id_user']) ? (int)$utilisateur['id_user'] : (isset($utilisateur['id']) ? (int)$utilisateur['id'] : 0);
                        $updateUrl = "utilisateurUpdate.php?id={$id}";
                        $deleteUrl = "  utilisateurDelete.php?id={$id}";
                        ?>
                        <a href="<?= htmlspecialchars($updateUrl) ?>" class="btn btn-sm btn-warning me-1">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <a href="<?= htmlspecialchars($deleteUrl) ?>" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
            },
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100]
        });
    });
</script>
</body>
</html>
