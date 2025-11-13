<?php
require_once '../../src/modele/ModelePost.php';
require_once '../../src/repository/PostRepository.php';
require_once "../../src/bdd/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$idUser=$_SESSION['utilisateur']["id_user"];
?>
    <!doctype html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>FORUM • LPRS</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
              crossorigin="anonymous">
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
            <li class="nav-item"><a href="../accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
            <li class="nav-item"><a href="../evenements.php" class="btn btn-outline-light">Évènements</a></li>
            <li class="nav-item"><a href="../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
            <li class="nav-item"><a href="../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
            <li class="nav-item"><a href="../emplois.php" class="btn btn-outline-light me-2">Emplois</a></li>
            <li class="nav-item"><a href="../forum.php" class="btn btn-outline-light me-2 active me-2">Forum</a></li>
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


    <main>

<?php
if(!isset($_SESSION['utilisateur'])){
    echo'<h5> Vous etes pas connecté. Veuillez vous connecter</h5>
<a  class="btn btn-secondary" href="../connexion.php" role="button">Se connecter</a>
<p>Erreur : Identify yourself who are you</p>';
}
?>
<form action="../../src/treatment/traitementAjoutPost.php" method="post">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        <input type="hidden" id="id_user" name="ref_user" value="<?=$idUser; ?>">
        <label for="titre_post"> Titre du Post</label>
        <input class="form-control" type="text" id="titre_post" name="titre_post" placeholder="Entrez le titre du Post">
        <label for="contenu_post">Contenue du Post</label>
        <textarea class="form-control" type="text" id="contenu_post" name="contenu_post" placeholder="Entrez le contenue du Post"></textarea>
    <!--  <label for="canal">Canal de discussion</label>
ajouter une fonctionalité pour les canal en fonction des roles
Un petit select qui si le role est [insert role] alors on affiche le canal [insert canal]-->
        <button class="btn btn-outline-primary" type="submit">Valider</button>
        </form>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
