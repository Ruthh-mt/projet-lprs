<?php
session_start();

$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0].'/public';

require_once '../../src/modele/ModelePostuler.php';
require_once '../../src/repository/PostulerRepository.php';
require_once "../../src/bdd/config.php";

$id_offre = $_GET['id'];
$postulerRepository = new PostulerRepository();
$candidaturesPostule = $postulerRepository->findOffreAndUser($_SESSION['utilisateur']['id_user'], $id_offre);

/* CONFIG DOSSIER */
$cvDossier = "../../src/treatment/telechargement/candidatures/";
$nomUser = strtolower($_SESSION['utilisateur']['nom']);
$prenom_user = strtolower($_SESSION['utilisateur']['prenom']);
$modeleFichier = $cvDossier . "cv_" . $nomUser . "_" . $prenom_user . ".*";
$fichier = glob($modeleFichier);
$cvChemin = !empty($files) ? $fichier[0] : null;
$cvUrl = $cvChemin ? "/projet-lprs/src/treatment/telechargement/candidatures/" . basename($cvChemin) : null;

/* SUPPR CV */
if (isset($_POST['delete_cv'])) {
    if ($cvChemin && file_exists($cvChemin)) {
        unlink($cvPath);
    }
    header("Location: ".$_SERVER['REQUEST_URI']);
    exit;
}

/*UPLOAD CV */
if (isset($_POST['update_candidature']) && isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {

    $tmp = $_FILES['cv']['tmp_name'];
    $extension = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, ['pdf', 'doc', 'docx'])) {
        die("Format non autorisé");
    }

    // Supprimer l'ancien CV si existe
    foreach (glob($modeleFichier) as $old) {
        unlink($old);
    }

    // Nouveau nom propre
    $newName = "cv_{$nomUser}_{$prenom_user}." . $extension;
    $destination = $cvDossier . $newName;

    move_uploaded_file($tmp, $destination);

    header("Location: ".$_SERVER['REQUEST_URI']);
    exit;
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
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" href="">

    <link rel="stylesheet" href="">



    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


</head>

<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="../accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://giffiles.alphacoders.com/208/208817.gif" class="rounded-circle mx-3" style="max-width: 48px;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="../accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="../evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="../emplois.php" class="btn btn-outline-light active me-2">Emplois</a></li>
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
                    <li><a class="dropdown-item" href="../connexion.php"><i class="bi bi-box-arrow-in-right"></i> Connexion</a></li>
                    <li><a class="dropdown-item" href="../inscription.php"><i class="bi bi-person-plus"></i> Inscription</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</header>
<body class="bg-light">

<section class="bg-info text-white text-center py-2 mb-4">
    <h1>Gestion Étudiant</h1>
</section>

<div class="container mt-4">
    <div class="row">

        <!-- FORM -->
        <div class="col-md-6">

            <h2 class="fw-bold mb-3"><?= htmlspecialchars($candidaturesPostule['titre']) ?></h2>

            <form action="/projet-lprs/src/treatment/traitementUpdatePostuler.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="ref_offre" value="<?= htmlspecialchars($id_offre) ?>">
                
                <div class="mb-3">
                    <label for="motivation" class="form-label">Lettre de motivation</label>
                    <textarea id="motivation" name="motivation" class="form-control" rows="5" required><?= 
                        htmlspecialchars($candidaturesPostule['motivation'] ?? '') 
                    ?></textarea>
                </div>

                <!-- Champs fichier -->
                <input type="file" name="cv" id="cv" class="d-none" accept=".pdf,.doc,.docx">

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" name="delete_candidature" class="btn btn-outline-danger">
                        Supprimer la candidature
                    </button>

                    <button type="submit" name="update_candidature" value="1" class="btn btn-outline-success">
                        Modifier la candidature
                    </button>
                </div>

            </form>
        </div>

        <!-- CV  -->
        <div class="col-md-6">

            <h4 class="text-center mb-3">CV</h4>

            <?php if ($cvUrl): ?>

                <div class="card shadow-sm p-3">
                    <div class="d-flex align-items-center">
                        <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png"
                             width="60" class="me-3">

                        <div>
                            <h5 class="mb-0"><?= htmlspecialchars(basename($cvChemin)) ?></h5>
                            <small class="text-muted">
                                Ajouté le <?= date("d/m/Y", filemtime($cvChemin)) ?>
                            </small>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <a href="<?= $cvUrl ?>" target="_blank" class="btn btn-primary btn-sm">
                            Voir
                        </a>

                        <button class="btn btn-success btn-sm"
                                onclick="document.getElementById('cv').click();">
                            Modifier
                        </button>

                        <form method="post">
                            <input type="hidden" name="delete_cv" value="1">
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Supprimer le CV ?');">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>

            <?php else: ?>

                <div class="card shadow-sm p-4 text-center">
                    <i class="bi bi-file-earmark fs-1 text-secondary"></i>
                    <p class="text-muted">Aucun CV disponible.</p>

                    <button class="btn btn-success"
                            onclick="document.getElementById('cv').click();">
                        Ajouter un CV
                    </button>
                </div>

            <?php endif; ?>

        </div>

    </div>
</div>

</body>
</html>
