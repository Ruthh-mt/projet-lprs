<?php
require_once("../../src/bdd/config.php");
session_start();

$pdo = (new Config())->connexion();

$ref_offre = $_POST['id_offre'] ?? null;
if (!$ref_offre) {
    echo "<script>alert('Aucune offre sélectionnée.'); window.location.href='../emplois.php';</script>";
    exit;
}
$sql = "SELECT * FROM postuler  WHERE ref_offre = ? and ref_user = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$ref_offre,$_SESSION['utilisateur']['id_user']]);
$candidature = $stmt->fetch();
$motivation = $candidature['motivation'];
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Postuler à une offre • LPRS</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .section-offre {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .offre-header {
            background-color: #212529;
            color: white;
            border-radius: .75rem .75rem 0 0;
            padding: 1.5rem;
            border-bottom: 2px solid #0d6efd;
        }

        .offre-header h2 {
            margin: 0;
        }

        .card {
            border: 1px solid rgba(0,0,0,0.08);
            padding: 24px;
            border-radius: 12px;
            background-color: #fff;
        }

        label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        input[type=text],
        input[type=date],
        input[type=email],
        input[type=tel],
        textarea,
        select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.8);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }
    </style>
</head>

<body>


<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="../accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://media.tenor.com/1DV7nkfj5OkAAAAM/blobgodeto-blobdance.gif"
                 class="rounded-circle mx-3"
                 style="max-width: 15%; height: auto;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="../accueil.php" class="btn btn-outline-light active dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="../evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="" class="btn btn-outline-light me-2">Emplois</a></li>
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


<section class="container banner bg-danger text-warning text-center py-1 rounded border">
    <h1>Cette page est censé être pour le gestionnaire</h1>
</section>



<!-- SECTION FORMULAIRE -->
<div class="container mb-5">
    <div class="section-offre">

        <!-- Bandeau titre -->
        <div class="offre-header d-flex justify-content-between align-items-center">
            <h2 class="fw-bold">Postuler</h2>
            <button type="button" class="btn btn-outline-light" onclick="window.location.href='../emplois.php'">
                <i class="bi bi-arrow-left-circle"></i> Retour
            </button>
        </div>

        <!-- Formulaire de candidature -->
        <form class="card mt-4"
              action="../../src/treatment/traitementUpdatePostuler.php"
              method="POST"
              enctype="multipart/form-data">

            <input type="hidden" name="ref_offre" value="<?= htmlspecialchars($ref_offre) ?>">


            <div class="mb-3">
                <label>
                    <textarea name="lettre" class="form-control" required> <?php echo $motivation ?>  </textarea>
                </label>
            </div>

            <div class="mb-3">
                <label for="cv" class="form-label">CV (PDF, DOCX) — optionnel</label>
                <input id="cv" name="cv" type="file" class="form-control" accept=".pdf,.doc,.docx">
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="consentement" name="consentement" required>
                <label class="form-check-label" for="consentement">
                    J'accepte que mes données soient utilisées pour traiter ma candidature.
                </label>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send"></i> Envoyer la candidature
                </button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='../../view/candidatures.php'">
                    <i class="bi bi-arrow-left"></i> Retour
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
