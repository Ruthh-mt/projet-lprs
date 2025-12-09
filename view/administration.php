<?php
$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0] . '/public';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../src/bdd/config.php';
require_once '../src/repository/UtilisateurRepository.php';
require_once '../src/repository/EvenementRepository.php';

$config = new Config();
$pdo    = $config->connexion();

// Repositories
$utilisateurRepo = new UtilisateurRepository();
$evenementRepo   = new EvenementRepository();

// Statistiques utilisateurs
$totalUtilisateurs          = (int) $utilisateurRepo->countAllUtilisateurs();
$utilisateursNonValidesRaw  = $utilisateurRepo->findNonValides();
$nbUtilisateursNonValides   = is_array($utilisateursNonValidesRaw) ? count($utilisateursNonValidesRaw) : 0;
$nbUtilisateursValides      = max(0, $totalUtilisateurs - $nbUtilisateursNonValides);

// Statistiques entreprises / partenaires
$nbEntreprises = (int) $pdo->query("SELECT COUNT(*) FROM fiche_entreprise")->fetchColumn();
$nbPartenaires = (int) $pdo->query("SELECT COUNT(*) FROM partenaire")->fetchColumn();

// Statistiques offres & candidatures
$nbOffres                = (int) $pdo->query("SELECT COUNT(*) FROM offre")->fetchColumn();
$nbCandidatures          = (int) $pdo->query("SELECT COUNT(*) FROM postuler")->fetchColumn();
$nbCandidaturesEnAttente = (int) $pdo->query("SELECT COUNT(*) FROM postuler WHERE est_accepte IS NULL")->fetchColumn();
$nbCandidaturesAcceptees = (int) $pdo->query("SELECT COUNT(*) FROM postuler WHERE est_accepte = 1")->fetchColumn();
$nbCandidaturesRefusees  = (int) $pdo->query("SELECT COUNT(*) FROM postuler WHERE est_accepte = 0")->fetchColumn();

// Statistiques évènements
$nbEvenements           = (int) $evenementRepo->countAllEvenement();
$nbEvenementsEnAttente  = (int) $pdo->query("SELECT COUNT(*) FROM evenement WHERE est_valide = 0")->fetchColumn();
$nbEvenementsValides    = max(0, $nbEvenements - $nbEvenementsEnAttente);

// Statistiques forum
$nbPosts        = (int) $pdo->query("SELECT COUNT(*) FROM post")->fetchColumn();
$nbPostsGeneral = (int) $pdo->query("SELECT COUNT(*) FROM post WHERE canal = 'general'")->fetchColumn();
$nbPostsProfEtud  = (int) $pdo->query("SELECT COUNT(*) FROM post WHERE canal = 'profediant'")->fetchColumn();
$nbPostsAlumniEnt = (int) $pdo->query("SELECT COUNT(*) FROM post WHERE canal = 'entrumnis'")->fetchColumn();
$nbPostsAdmin     = (int) $pdo->query("SELECT COUNT(*) FROM post WHERE canal = 'admin'")->fetchColumn();
$nbReponses       = (int) $pdo->query("SELECT COUNT(*) FROM reponse")->fetchColumn();

// Listes détaillées
// Derniers comptes à valider
$stmt = $pdo->prepare("
    SELECT id_user, nom, prenom, email, role
    FROM utilisateur
    WHERE est_valide = 0
    ORDER BY id_user DESC
    LIMIT 5
");
$stmt->execute();
$utilisateursEnAttente = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Dernières offres publiées
$stmt = $pdo->query("
    SELECT id_offre, titre, type, etat
    FROM offre
    ORDER BY id_offre DESC
    LIMIT 5
");
$dernieresOffres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prochains évènements (les 5 plus récents)
$stmt = $pdo->query("
    SELECT id_evenement, titre_eve, date_heure_evenement, status, est_valide
    FROM evenement
    ORDER BY date_heure_evenement DESC
    LIMIT 5
");
$prochainsEvenements = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Derniers posts sur le forum
$stmt = $pdo->query("
    SELECT id_post, titre_post, canal, date_heure_post
    FROM post
    ORDER BY date_heure_post DESC
    LIMIT 5
");
$derniersPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pourcentages pour les barres de progression
$pourcentageUtilisateursValides    = $totalUtilisateurs > 0 ? round(($nbUtilisateursValides / $totalUtilisateurs) * 100) : 0;
$pourcentageCandidaturesAcceptees  = $nbCandidatures > 0 ? round(($nbCandidaturesAcceptees / $nbCandidatures) * 100) : 0;
$pourcentageEvenementsValides      = $nbEvenements > 0 ? round(($nbEvenementsValides / $nbEvenements) * 100) : 0;
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ADMIN • LPRS</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          crossorigin="anonymous">

    <style>
        body {
            background-color: #f5f5f5;
        }
        .card-stat {
            border-radius: 1rem;
        }
        .icon-pill {
            border-radius: 999px;
            padding: .75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .badge-soft-warning {
            background-color: rgba(255, 193, 7, .15);
            color: #856404;
        }
        .badge-soft-success {
            background-color: rgba(25, 135, 84, .15);
            color: #0f5132;
        }
        .badge-soft-danger {
            background-color: rgba(220, 53, 69, .15);
            color: #842029;
        }
    </style>
</head>
<body>

<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="../accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none align-items-center">
            <img src="https://i.pinimg.com/originals/a0/50/1e/a0501e6dc22dcfde397299e4234e75a.gif"
                 class="mx-3" style="max-width: 48px;" alt="Logo LPRS">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>

    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="accueil.php" class="btn btn-outline-light me-2">Accueil</a></li>
        <li class="nav-item"><a href="evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="emplois.php" class="btn btn-outline-light me-2">Emplois</a></li>
        <li class="nav-item"><a href="forum.php" class="btn btn-outline-light me-2">Forum</a></li>
        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Gestionnaire'): ?>
            <li class="nav-item">
                <a href="administration.php" class="btn btn-outline-warning active me-2">Administration</a>
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
                        <img src="<?= htmlspecialchars($prefix . '/' . ltrim($avatar, '/')) ?>"
                             alt="Avatar"
                             class="rounded-circle border border-2 border-light"
                             width="40" height="40"
                             style="object-fit: cover;">
                    <?php else: ?>
                        <i class="bi bi-person-circle fs-3 text-light"></i>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li>
                        <a class="dropdown-item text-primary" href="account/accountRead.php">
                            <i class="bi bi-person"></i> Mon compte
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="../src/treatment/traitementDeconnexion.php">
                            <i class="bi bi-box-arrow-right"></i> Déconnexion
                        </a>
                    </li>
                </ul>
            <?php else: ?>
                <a href="#" class="d-inline-block text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-3 text-light"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li>
                        <a class="dropdown-item text-primary" href="connexion.php">
                            <i class="bi bi-box-arrow-in-right"></i> Connexion
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-success" href="inscription.php">
                            <i class="bi bi-person-plus"></i> Inscription
                        </a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</header>

<nav class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom text-white bg-dark">
    <div class="nav col mb-2 justify-content-center mb-md-0">
        <div class="btn-group mx-1" role="group" aria-label="Navigation administration">
            <a href="crudEntreprise/entrepriseRead.php" class="btn btn-outline-info">Entreprise</a>
            <a href="crudEvenement/evenementListe.php" class="btn btn-outline-info">Évènement</a>
            <a href="crudFormation/formationRead.php" class="btn btn-outline-info">Formation</a>
            <a href="crudGestionnaire/gestionnaireRead.php" class="btn btn-outline-info">Gestionnaire</a>
            <a href="crudOffre/offreListe.php" class="btn btn-outline-info">Offre</a>
            <a href="crudPartenaire/partenaireRead.php" class="btn btn-outline-info">Partenaire</a>
            <a href="crudPost/postListe.php" class="btn btn-outline-info">Post</a>
            <a href="crudReponse/reponseRead.php" class="btn btn-outline-info">Réponses</a>
            <a href="crudUtilisateur/utilisateurRead.php" class="btn btn-outline-info">Utilisateur</a>
        </div>
        <a href="crudUtilisateur/utilisateurAValider.php" class="btn btn-outline-warning ms-2">
            À valider
            <?php if ($nbUtilisateursNonValides > 0): ?>
                <span class="badge bg-warning text-dark ms-1"><?= (int) $nbUtilisateursNonValides ?></span>
            <?php endif; ?>
        </a>
    </div>
</nav>

<main class="container mb-5">

    <!-- Titre + meta -->
    <section class="mb-4">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div>
                <h1 class="h3 mb-1">Tableau de bord administration</h1>
                <p class="text-muted mb-0">
                    Vue d'ensemble des utilisateurs, entreprises, offres, évènements et activité du forum.
                </p>
            </div>
            <div class="mt-3 mt-md-0 text-md-end">
                <span class="badge bg-secondary">
                    Dernière mise à jour : <?= date('d/m/Y H:i') ?>
                </span>
            </div>
        </div>
    </section>

    <!-- Statistiques globales -->
    <section class="row g-3 mb-4">
        <!-- Utilisateurs -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card card-stat shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="text-uppercase text-muted small mb-1">Utilisateurs</h6>
                            <h3 class="mb-0"><?= (int) $totalUtilisateurs ?></h3>
                            <span class="small text-muted">Comptes au total</span>
                        </div>
                        <span class="icon-pill bg-primary-subtle">
                            <i class="bi bi-people text-primary fs-4"></i>
                        </span>
                    </div>

                    <div class="mt-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span><?= (int) $nbUtilisateursValides ?> validés</span>
                            <span><?= (int) $pourcentageUtilisateursValides ?>%</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: <?= (int) $pourcentageUtilisateursValides ?>%"></div>
                        </div>

                        <?php if ($nbUtilisateursNonValides > 0): ?>
                            <p class="mt-2 mb-0 small text-warning">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <?= (int) $nbUtilisateursNonValides ?> compte(s) en attente de validation
                            </p>
                        <?php else: ?>
                            <p class="mt-2 mb-0 small text-muted">
                                Aucun compte en attente de validation.
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 d-flex justify-content-between">
                    <a href="crudUtilisateur/utilisateurRead.php" class="btn btn-sm btn-outline-primary">
                        Gérer les utilisateurs
                    </a>
                    <a href="crudUtilisateur/utilisateurAValider.php" class="btn btn-sm btn-outline-warning">
                        Comptes à valider
                    </a>
                </div>
            </div>
        </div>

        <!-- Entreprises / partenaires -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card card-stat shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="text-uppercase text-muted small mb-1">Réseau professionnel</h6>
                            <h3 class="mb-0"><?= (int) $nbEntreprises ?></h3>
                            <span class="small text-muted">Entreprises référencées</span>
                        </div>
                        <span class="icon-pill bg-info-subtle">
                            <i class="bi bi-building text-info fs-4"></i>
                        </span>
                    </div>
                    <p class="mt-3 mb-0 small text-muted">
                        Partenaires actifs : <strong><?= (int) $nbPartenaires ?></strong>
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 d-flex justify-content-between">
                    <a href="crudEntreprise/entrepriseRead.php" class="btn btn-sm btn-outline-info">
                        Voir les entreprises
                    </a>
                    <a href="crudPartenaire/partenaireRead.php" class="btn btn-sm btn-outline-secondary">
                        Voir les partenaires
                    </a>
                </div>
            </div>
        </div>

        <!-- Offres & candidatures -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card card-stat shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="text-uppercase text-muted small mb-1">Offres & candidatures</h6>
                            <h3 class="mb-0"><?= (int) $nbOffres ?></h3>
                            <span class="small text-muted">Offres publiées</span>
                        </div>
                        <span class="icon-pill bg-success-subtle">
                            <i class="bi bi-briefcase text-success fs-4"></i>
                        </span>
                    </div>

                    <ul class="list-unstyled small mt-3 mb-0">
                        <li>Total candidatures : <strong><?= (int) $nbCandidatures ?></strong></li>
                        <li class="text-muted mt-1">
                            <span class="badge badge-soft-warning me-1">
                                En attente : <?= (int) $nbCandidaturesEnAttente ?>
                            </span>
                            <span class="badge badge-soft-success me-1">
                                Acceptées : <?= (int) $nbCandidaturesAcceptees ?>
                            </span>
                            <span class="badge badge-soft-danger">
                                Refusées : <?= (int) $nbCandidaturesRefusees ?>
                            </span>
                        </li>
                    </ul>

                    <div class="mt-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Taux d'acceptation</span>
                            <span><?= (int) $pourcentageCandidaturesAcceptees ?>%</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: <?= (int) $pourcentageCandidaturesAcceptees ?>%"></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 d-flex justify-content-between">
                    <a href="crudOffre/offreListe.php" class="btn btn-sm btn-outline-success">
                        Gérer les offres
                    </a>
                    <a href="candidatures.php" class="btn btn-sm btn-outline-secondary">
                        Voir les candidatures
                    </a>
                </div>
            </div>
        </div>

        <!-- Évènements -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card card-stat shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="text-uppercase text-muted small mb-1">Évènements</h6>
                            <h3 class="mb-0"><?= (int) $nbEvenements ?></h3>
                            <span class="small text-muted">Évènements planifiés</span>
                        </div>
                        <span class="icon-pill bg-warning-subtle">
                            <i class="bi bi-calendar-event text-warning fs-4"></i>
                        </span>
                    </div>

                    <ul class="list-unstyled small mt-3 mb-0">
                        <li>
                            <span class="badge badge-soft-success me-1">
                                Validés : <?= (int) $nbEvenementsValides ?>
                            </span>
                            <span class="badge badge-soft-warning">
                                En attente : <?= (int) $nbEvenementsEnAttente ?>
                            </span>
                        </li>
                    </ul>

                    <div class="mt-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Taux d'évènements validés</span>
                            <span><?= (int) $pourcentageEvenementsValides ?>%</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-info" role="progressbar"
                                 style="width: <?= (int) $pourcentageEvenementsValides ?>%"></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 d-flex justify-content-between">
                    <a href="crudEvenement/evenementListe.php" class="btn btn-sm btn-outline-warning">
                        Gérer les évènements
                    </a>
                    <a href="evenements.php" class="btn btn-sm btn-outline-secondary">
                        Vue publique
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Activité forum + listes détaillées -->
    <section class="row g-3">
        <!-- Bloc forum -->
        <div class="col-12 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h2 class="h6 mb-0">Forum & réponses</h2>
                    <span class="badge bg-primary-subtle text-primary">
                        <i class="bi bi-chat-dots me-1"></i>
                        <?= (int) $nbPosts ?> posts • <?= (int) $nbReponses ?> réponses
                    </span>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-3">
                        <li>Canal général : <strong><?= (int) $nbPostsGeneral ?></strong></li>
                        <li>Étudiant / Professeur : <strong><?= (int) $nbPostsProfEtud ?></strong></li>
                        <li>Alumni / Entreprise : <strong><?= (int) $nbPostsAlumniEnt ?></strong></li>
                        <li>Gestionnaire : <strong><?= (int) $nbPostsAdmin ?></strong></li>
                    </ul>

                    <h3 class="h6 mt-3">Derniers posts</h3>
                    <?php if (!empty($derniersPosts)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($derniersPosts as $post): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong><?= htmlspecialchars($post['titre_post']) ?></strong><br>
                                            <span class="badge bg-light text-muted border">
                                                Canal : <?= htmlspecialchars($post['canal']) ?>
                                            </span>
                                        </div>
                                        <small class="text-muted ms-3">
                                            <?= htmlspecialchars(date('d/m/Y H:i', strtotime($post['date_heure_post']))) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted small mb-0">Aucun post récent.</p>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="forum.php" class="btn btn-sm btn-outline-primary">
                        Aller au forum
                    </a>
                    <a href="crudPost/postListe.php" class="btn btn-sm btn-outline-secondary">
                        Gérer les posts
                    </a>
                </div>
            </div>
        </div>

        <!-- Bloc utilisateurs à valider -->
        <div class="col-12 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h2 class="h6 mb-0">Utilisateurs à valider</h2>
                    <span class="badge bg-warning text-dark">
                        <?= (int) $nbUtilisateursNonValides ?> en attente
                    </span>
                </div>
                <div class="card-body">
                    <?php if (!empty($utilisateursEnAttente)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Rôle</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($utilisateursEnAttente as $u): ?>
                                    <tr>
                                        <td><?= (int) $u['id_user'] ?></td>
                                        <td><?= htmlspecialchars($u['nom']) ?></td>
                                        <td><?= htmlspecialchars($u['prenom']) ?></td>
                                        <td><?= htmlspecialchars($u['role']) ?></td>
                                        <td class="text-end">
                                            <form method="post"
                                                  action="../src/treatment/traitementValidationUtilisateur.php"
                                                  class="d-inline">
                                                <input type="hidden" name="id_user" value="<?= (int) $u['id_user'] ?>">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    Valider
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted small mb-0">
                            Aucun utilisateur en attente actuellement.
                        </p>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="crudUtilisateur/utilisateurAValider.php" class="btn btn-sm btn-outline-warning">
                        Voir tous les comptes à valider
                    </a>
                </div>
            </div>
        </div>

        <!-- Bloc offres + évènements -->
        <div class="col-12 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">Offres & évènements récents</h2>
                </div>
                <div class="card-body">
                    <h3 class="h6">Dernières offres</h3>
                    <?php if (!empty($dernieresOffres)): ?>
                        <div class="list-group list-group-flush mb-3">
                            <?php foreach ($dernieresOffres as $offre): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong><?= htmlspecialchars($offre['titre']) ?></strong><br>
                                            <small class="text-muted">
                                                Type : <?= htmlspecialchars($offre['type']) ?>
                                            </small>
                                        </div>
                                        <span class="badge bg-light text-muted border">
                                            <?= htmlspecialchars($offre['etat']) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted small">Aucune offre trouvée.</p>
                    <?php endif; ?>

                    <h3 class="h6 mt-3">Prochains évènements</h3>
                    <?php if (!empty($prochainsEvenements)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($prochainsEvenements as $eve): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong><?= htmlspecialchars($eve['titre_eve']) ?></strong><br>
                                            <small class="text-muted">
                                                <?= htmlspecialchars(date('d/m/Y H:i', strtotime($eve['date_heure_evenement']))) ?>
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-light text-muted border d-block mb-1">
                                                <?= htmlspecialchars($eve['status']) ?>
                                            </span>
                                            <?php if ((int) $eve['est_valide'] === 0): ?>
                                                <span class="badge badge-soft-warning">
                                                    À valider
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-soft-success">
                                                    Validé
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted small mb-0">Aucun évènement trouvé.</p>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 d-flex justify-content-between">
                    <a href="crudOffre/offreListe.php" class="btn btn-sm btn-outline-success">
                        Gérer les offres
                    </a>
                    <a href="crudEvenement/evenementListe.php" class="btn btn-sm btn-outline-warning">
                        Gérer les évènements
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
</body>
</html>
