<?php
$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0].'/public';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = 'Entreprise';

if (!isset($_GET['id_fiche_entreprise']) || !is_numeric($_GET['id_fiche_entreprise'])) {
    $_SESSION['error'] = "ID de fiche entreprise invalide.";
    header('Location: ./entrepriseRead.php');
    exit();
}

$id_fiche_entreprise = (int)$_GET['id_fiche_entreprise'];

require_once __DIR__ . '../../../src/repository/FicheEntrepriseRepository.php';
$ficheEntrepriseRepository = new FicheEntrepriseRepository();

$fiche = $ficheEntrepriseRepository->getFicheEntrepriseById($id_fiche_entreprise);

if (!$fiche) {
    $_SESSION['error'] = "Fiche entreprise non trouvée.";
    header('Location: ./entrepriseRead.php');
    exit();
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ADMIN • LPRS - Supprimer une entreprise</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
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
            <a href="../crudEntreprise/entrepriseRead.php" class="btn btn-outline-info active">Entreprise</a>
            <a href="../crudEvenement/evenementListe.php" class="btn btn-outline-info">Évènement</a>
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
<main class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h2 class="h4 mb-0">Supprimer une entreprise</h2>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_SESSION['error']); ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="alert alert-danger">
                        <h5 class="alert-heading">Attention !</h5>
                        <p>Vous êtes sur le point de supprimer définitivement l'entreprise suivante : <?= htmlspecialchars($fiche['nom_entreprise'] ?? 'N/A') ?></p>
                        <ul class="mb-0">
                            <li><strong>ID :</strong> <?= htmlspecialchars($fiche['id_fiche_entreprise'] ?? 'N/A') ?></li>
                            <li><strong>Nom :</strong> <?= htmlspecialchars($fiche['nom_entreprise'] ?? 'N/A') ?></li>
                            <li><strong>Ville :</strong> <?= htmlspecialchars($fiche['adresse_entreprise'] ?? 'Non spécifiée') ?></li>
                            <li><strong>Site :</strong> <?= htmlspecialchars($fiche['adresse_web'] ?? 'Non spécifiée') ?></li>
                        </ul>
                        <hr>
                        <p class="mb-0 fw-bold">Cette action est irréversible. Êtes-vous sûr de vouloir continuer ?</p>
                    </div>

                    <form action="../../src/treatment/traitementDeleteEntreprise.php" method="post" class="d-flex justify-content-between">
                        <input type="hidden" name="id_fiche_entreprise" value="<?= $id_fiche_entreprise ?>">
                        <a href="entrepriseRead.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <button type="submit" class="btn btn-outline-danger");">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>