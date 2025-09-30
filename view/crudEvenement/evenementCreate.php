<?php
if (session_status() === PHP_SESSION_NONE) {
    // ajouter que seulement un user connecter et qui n'est pas gestionnaire peut se connecter
    session_start();
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
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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
<form action="../../src/treatment/traitementAjoutEvenement.php" method="post">
    <label for="titre_eve"> Titre de l'evenement</label>
    <input class="form-control" type="text" id="titre_eve" name="titre_eve" placeholder="Entrez le titre de l'eve">
    <label for="type_eve">Type de l'evenement</label>
    <input class="form-control" type="text" id="type_eve" name="type_eve" placeholder="Entrez le type de l'eve">
    <label for="desc_eve">Description de l'evenement</label>
    <textarea class="form-control" type="text" id="desc_eve" name="desc_eve" placeholder="Entrez la description de l'evenement"></textarea>
    <label for="lieu_eve">Lieu de l'evenement</label>
    <input class="form-control" type="text" id="lieu_eve" name="lieu_eve" placeholder="Entrez le type de l'eve">
    <label for="element_eve">Element pour l'evenement</label>
    <textarea class="form-control" type="text" id="element_eve" name="element_eve" placeholder="Entrez les element requis pour l'eve"></textarea>
    <label for="nb_plc">Type de l'evenement</label>
    <input class="form-control" type="number" id="nb_plc" name="nb_plc" placeholder="Entrez le nombres de place disponible">
    <button class="btn btn-outline-primary" type="submit">Valider</button>
</form>


