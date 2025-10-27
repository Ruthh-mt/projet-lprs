<?php
require_once "../../src/modele/Evenement.php";
require_once "../../src/modele/EvenementUser.php";
require_once "../../src/repository/EvenementRepository.php";
require_once "../../src/repository/EvenementUserRepository.php";
require_once "../../src/bdd/config.php";
$id='';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(isset($_GET['id'])){
    $id=$_GET["id"];
}
$evenement=new Evenement(["idEvenement" =>$id]);
$evenementRepo=new EvenementRepository();
$evenement=$evenementRepo->getAnEvenement($evenement)
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EVENEMENTS • LPRS</title>
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
        <li class="nav-item"><a href="../evenements.php" class="btn btn-outline-light active me-2">Évènements</a></li>
        <li class="nav-item"><a href="../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="../emplois.php" class="btn btn-outline-light me-2">Emplois</a></li>
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
<main>
        <div class="card">
            <h5 class="card-header"><?php echo $evenement->getTitreEvenement(); ?></h5>
            <div class="card-body">
                <h5 class="card-title">Information</h5>
                <form action="../../src/treatment/traitementInscriptionEvenement.php" method="post">
                <input type="hidden" value="<?=$evenement->getIdEvenement();?>" name="ref_eve">
                <input type="hidden" value="<?=$_SESSION["utilisateur"]["id_user"]?>" name="refUser">
                <p class="card-text">Type d'evenement : <input type="text" readonly class="form-control-plaintext" id="type_eve" name="type_eve" value="<?php echo $evenement->getTypeEvenement(); ?>"></p>
                <p class="card-text">Lieu de l'evenement : <input type="text" readonly class="form-control-plaintext" id="lieu_eve" name="lieu_eve"  value="<?php echo $evenement->getLieuEvenement(); ?>"></p>
                <p class="card-text">Description : <textarea class="form-control" readonly class="form-control-plaintext" id="desc_eve" name="desc_eve"  rows="5"> <?php echo $evenement->getDescEvenement(); ?></textarea class="textarea"></p>
                <p class="card-text">Element necessaires : <input type="text" readonly class="form-control-plaintext" id="element_eve" name="element_eve"  value="<?php echo $evenement->getElementEvenement(); ?>"></p>
                <p class="card-text">Nombre de place disponible : <input type="number" readonly class="form-control-plaintext" id="nb_place" name="nb_place"  value="<?php echo $evenement->getNbPlace(); ?>"></p>
                <?php
                $evenementUserRepository=new EvenementUserRepository();
                $superviseur=$evenementUserRepository->getSuperviseur($evenement->getIdEvenement());
                $eveUser=new EvenementUser(["refUser" => $_SESSION['utilisateur']['id_user']]);
                $estInscrit=$evenementUserRepository->verifDejaInscritEvenement($eveUser);
                if(!$_SESSION['utilisateur']['id_user'] == $superviseur->getRefUser()) {
                    if(!$estInscrit) {
                        echo'<button class="btn btn-primary" type="submit">Se desinscrire</button>';
                    }
                    else{
                    echo'<button class="btn btn-primary" type="submit">Participer</button>';
                    }
                }
                ?>
    </form>

    <?php
                if($_SESSION['utilisateur']['id_user'] == $superviseur->getRefUser()) {
                    echo'<a href="evenementUpdate?id='.$evenement->getIdEvenement().'.php"><button class="btn btn-primary">Modifier</button></a> ';
                }

                ?>
            </div>
        </div>
</main>

