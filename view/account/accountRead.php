<?php
$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0].'/public';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['utilisateur'])) {
    header('Location: ../Connexion.php');
    exit();
}
$utilisateur = $_SESSION['utilisateur'];
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<header
        class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
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
                <a href="../administration.php" class="btn btn-outline-warning me-2">Administration</a>
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
<section class="container banner text-center bg-dark text-white text-center py-3 rounded">
    <h1>Mon compte</h1>
</section>
<section class="container rounded my-3">
    <div class="justify-content-center my-3">
        <div class="d-grid gap-2">
            <a href="AccountUpdate.php" class="btn btn-outline-secondary">Modifier</a>
        </div>
        <div class="row">
            <div class="col card m-3 pt-3">
                <div class="card mb-3" style="max-width: 540px;">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <?php if ($avatar): ?>
                                <img src="<?= $prefix.htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil" class="rounded-circle mt-4 ms-1" style="max-width:100%;max-height:100%;object-fit:cover;">
                            <?php else: ?>
                                <i class="bi bi-person-circle ms-3" style="font-size: 1000%;"></i>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Informations personnelles</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Prénom :</strong> <?= htmlspecialchars($utilisateur['prenom']) ?></li>
                                    <li class="list-group-item"><strong>Nom : </strong><?= htmlspecialchars($utilisateur['nom'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false) ?>
                                    </li>
                                    <li class="list-group-item"><strong>Email
                                            :</strong> <?= htmlspecialchars($utilisateur['email']) ?></li>
                                    <li class="list-group-item"><strong>Rôle
                                            :</strong> <?= isset($utilisateur['role']) ? htmlspecialchars($utilisateur['role']) : 'Non renseigné' ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col card m-3">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Informations additionnelles</h5>
                    <ul class="list-group list-group-flush">
                        <?php if ($utilisateur['role'] === 'Étudiant'): ?>
                            <li class="list-group-item"><strong>Promotion :</strong> <?= htmlspecialchars($utilisateur['annee_promo'] ?? 'Non renseigné') ?></li>
                            <li class="list-group-item"><strong>Classe / Formation :</strong> <?= htmlspecialchars($utilisateur['classe'] ?? 'Non renseigné') ?></li>
                            <li class="list-group-item"><strong>CV :</strong>
                                <?php if (!empty($utilisateur['cv'])): ?>
                                    <a href="<?= htmlspecialchars($utilisateur['cv']) ?>" target="_blank">Voir le CV</a>
                                <?php else: ?>
                                    Non renseigné
                                <?php endif; ?>
                            </li>

                        <?php elseif ($utilisateur['role'] === 'Alumni'): ?>
                            <li class="list-group-item"><strong>Promotion :</strong> <?= htmlspecialchars($utilisateur['annee_promo'] ?? 'Non renseigné') ?></li>
                            <li class="list-group-item"><strong>Poste :</strong> <?= htmlspecialchars($utilisateur['poste'] ?? 'Non renseigné') ?></li>
                            <li class="list-group-item"><strong>CV :</strong>
                                <?php if (!empty($utilisateur['cv'])): ?>
                                    <a href="<?= htmlspecialchars($utilisateur['cv']) ?>" target="_blank">Voir le CV</a>
                                <?php else: ?>
                                    Non renseigné
                                <?php endif; ?>
                            </li>

                        <?php elseif ($utilisateur['role'] === 'Professeur'): ?>
                            <li class="list-group-item"><strong>Spécialité :</strong> <?= htmlspecialchars($utilisateur['specialite'] ?? 'Non renseigné') ?></li>

                        <?php elseif ($utilisateur['role'] === 'Partenaire'): ?>
                            <li class="list-group-item"><strong>Poste :</strong> <?= htmlspecialchars($utilisateur['poste'] ?? 'Non renseigné') ?></li>
                            <li class="list-group-item"><strong>CV :</strong>
                                <?php if (!empty($utilisateur['cv'])): ?>
                                    <a href="<?= htmlspecialchars($utilisateur['cv']) ?>" target="_blank">Voir le CV</a>
                                <?php else: ?>
                                    Non renseigné
                                <?php endif; ?>
                            </li>
                        <?php else: ?>
                            <li class="list-group-item">Aucune information supplémentaire.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoIIfJcLyjU29tka7Sk3YSA8l7IgGKmFckcImFV8Qbsw3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>