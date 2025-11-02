<?php
require_once ("../../src/bdd/config.php");
$pdo  = (new Config())->connexion();
$sql =$pdo->prepare("SELECT * FROM  fiche_entreprise f  ");
$sql -> execute();
$entreprises = $sql -> fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Création d’une offre • LPRS</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* gris clair, contraste avec header sombre */
        }

        .section-offre {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .offre-header {
            background-color: #212529; /* plus sombre, cohérent avec le header du site */
            color: white;
            border-radius: .75rem .75rem 0 0;
            padding: 1.5rem;
            border-bottom: 2px solid #0d6efd; /* fine ligne bleue pour rappeler ton thème */
        }

        .offre-header h2 {
            margin: 0;
        }

        .offre-actions a {
            transition: all 0.2s ease-in-out;
        }

        .offre-actions a:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>

<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="../accueil.php" class="d-inline-flex text-decoration-none align-items-center">
            <img src="https://media.tenor.com/1DV7nkfj5OkAAAAM/blobgodeto-blobdance.gif"
                 class="rounded-circle mx-3"
                 style="max-width: 15%; height: auto;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>

    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="../../view/accueil.php" class="btn btn-outline-light me-2">Accueil</a></li>
        <li class="nav-item"><a href="../../view/evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="../../view/annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="../../view/listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="../../view/emplois.php" class="btn btn-outline-light active me-2">Emplois</a></li>
        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Gestionnaire'): ?>
            <li class="nav-item">
                <a href="../administration.php" class="btn btn-outline-warning me-2">Administration</a>
            </li>
        <?php endif; ?>
    </ul>

    <div class="col-2 btn-group md-3 me-3 text-end">
        <?php if (isset($_SESSION['utilisateur'])): ?>
            <a href="../account/accountRead.php" class="btn btn-outline-primary">Mon compte</a>
            <a href="../../src/treatment/traitementDeconnexion.php" class="btn btn-outline-danger">Déconnexion</a>
        <?php else: ?>
            <a href="../connexion.php" class="btn btn-outline-success">Connexion</a>
            <a href="../inscription.php" class="btn btn-outline-primary">Inscription</a>
        <?php endif; ?>
    </div>
</header>

<!-- SECTION FORMULAIRE -->
<div class="container mb-5">
    <div class="section-offre">

        <!-- Bandeau titre -->
        <div class="offre-header d-flex justify-content-between align-items-center">
            <h2 class="fw-bold">Création d’une offre d’emploi</h2>
            <button type="button" class="btn btn-outline-light" onclick="window.location.href='../emplois.php'">
                <i class="bi bi-arrow-left-circle"></i> Retour
            </button>
        </div>
        <!-- Formulaire -->
        <form class="mt-4" action="../../src/treatment/traitementAjoutOffre.php" method="post">


            <div class="mb-3">
                <label for="titre_poste" class="form-label">Titre du poste</label>
                <input class="form-control" type="text" id="titre_eve" name="titre_poste" placeholder="Entrez le titre du poste">
            </div>

            <div class="mb-3">
                <label for="desc_contrat" class="form-label">Description du contrat</label>
                <textarea class="form-control" id="desc_contrat" name="desc_contrat" rows="3" placeholder="Entrez la description du contrat"></textarea>
            </div>
            <div class="mb-3">
                <label for="mission" class="form-label">Mission</label>
                <textarea class="form-control" id="mission" name="mission" rows="3" ></textarea>
            </div>

            <div class="mb-3">
                <label for="type_contrat" class="form-label">Type de contrat</label>
                <select class="form-select" name="type_contrat">
                    <option value="cdi">CDI</option>
                    <option value="stage">Stage</option>
                    <option value="cdd">CDD</option>
                    <option value="alternance">Alternance</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="salaire" class="form-label">Salaire</label>
                <input class="form-control" type="number" id="salaire" name="salaire" placeholder="Entrez le salaire">
            </div>
            <div class="mb-3">
                <label for="entreprise" class="form-label">Entreprise</label>
                <select class="form-select" name="entreprise">
                <?php foreach ($entreprises as $entreprise) : ?>
                    <option value="<?= $entreprise['id_fiche_entreprise']?>"><?= $entreprise['nom_entreprise']?></option>
                <?php endforeach; ?>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit">Valider</button>
                <button class="btn btn-secondary" type="reset">Annuler</button>
                <a href="../../view/emplois.php" class="btn btn-outline-dark">Retour</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
