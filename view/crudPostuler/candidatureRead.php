<?php

require_once "../../src/bdd/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$ref_offre = $_GET['id'] ?? null;
$ref_user = $_SESSION['utilisateur']['id_user'] ?? null;
$bdd = new Config();
$pdo = $bdd -> connexion() ;

$sql = "SELECT * FROM postuler inner join offre
         on postuler.ref_offre = offre.id_offre 
                WHERE ref_user =? and  ref_offre =?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$ref_user, $ref_offre]);
$candidature= $stmt->fetch();

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détail d’un évènement • LPRS</title>

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
        label {
            font-weight: 600;
            margin-bottom: 6px;
        }
        input[type=text],
        input[type=number],
        textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.9);
        }
        textarea {
            min-height: 120px;
            resize: vertical;
        }
    </style>
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
        <li class="nav-item"><a href="../accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="../evenements.php" class="btn btn-outline-light ">Évènements</a></li>
        <li class="nav-item"><a href="../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="../emplois.php" class="btn btn-outline-light ">Emplois</a></li>
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
<body>


<section class="container banner bg-danger text-warning text-center py-1 rounded border">
    <h1>Cette page est censé être pour le gestionnaire</h1>
</section>


<!-- SECTION DETAIL -->
<div class="container mb-5">
    <div class="section-offre">
        <div class="offre-header d-flex justify-content-between align-items-center">
            <h2 class="fw-bold"><?= htmlspecialchars($candidature['titre'] )?></h2>
            <button type="button" class="btn btn-outline-light" onclick="window.location.href='../../view/candidatures.php'">
                <i class="bi bi-arrow-left-circle"></i> Retour aux candidatures
            </button>
        </div>


            <input type="hidden" name="id_offre" value="<?= htmlspecialchars($candidature['id_offre']) ?>">
            <input type="hidden" name="ref_user" value="<?= htmlspecialchars($_SESSION['utilisateur']['id_user']) ?>">

            <div class="mb-3">
                <label for="titre">Titre du poste</label>
                <input type="text" readonly class="form-control" id="titre"
                       value="<?= htmlspecialchars($candidature['titre']) ?>">
            </div>

            <div class="mb-3">
                <label for="type">Contrat</label>
                <input type="text" readonly class="form-control" id="type"
                       value="<?= htmlspecialchars($candidature['type']) ?>">
            </div>

            <div class="mb-3">
                <label for="desc">Description</label>
                <textarea readonly class="form-control" id="desc"><?= htmlspecialchars($candidature['description']) ?></textarea>
            </div>

        <?php if (isset($_SESSION['utilisateur']['id_user'] )) ?>
            <div class="text-center mt-3">
                <form action="candidatureDelete.php" method="post" style="display:inline;">
                    <input type="hidden" name="id_offre" value="<?= htmlspecialchars($candidature['id_offre']) ?>">
                    <input type="hidden" name="delete_candidature" value="1">
                    <button type="submit" class="btn btn-danger" title="Supprimer"
                            onclick="return confirm('Supprimer cette offre ?')">Supprimer votre candidature</button>
                </form>
                <form action="candidatureUpdate.php" method="post" style="display:inline;">
                    <input type="hidden" name="id_offre" value="<?= htmlspecialchars($candidature['id_offre']) ?>">
                    <input type="hidden" name="update_candidature" value="1">
                    <button type="submit"  class="btn btn-warning" title="modifier"  onclick="window.location.href='../../view/candidatureUpdate.php'">Modifier votre candidature
                    </button>
                </form>
            </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
