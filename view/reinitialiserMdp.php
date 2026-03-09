<?php
require_once "../src/repository/MdpResetRepository.php";
require_once "../src/modele/ModeleMdpReset.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$repo=new MdpResetRepository();
$mdpReset= new ModeleMdpReset([
        "token"=>$_GET['token']]);
$success=$repo->verifierToken($mdpReset);
$date = date('Y/m/d h:i:s ', time());
$token="";
if($success->expire_a <$date){
    $token=$_GET['token'];
}else{
    $_SESSION["toastr"]['type']="error";
    $_SESSION["toastr"]["message"]="Le token a expiré";
    header("location:connexion.php",$_SESSION["toastr"]["type"]);
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reinitialisation Mot de Passe • LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
</head>
<body class="bg-primary-subtle">
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://media.tenor.com/YzarFuImKCIAAAAj/blob-derpy.gif" class="mx-3" style="max-width: 48px;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="accueil.php" class="btn btn-outline-light active dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="emplois.php" class="btn btn-outline-light me-2">Emplois</a></li>
        <li class="nav-item"><a href="forum.php" class="btn btn-outline-light me-2">Forum</a></li>
        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Gestionnaire'): ?>
            <li class="nav-item">
                <a href="administration.php" class="btn btn-outline-warning me-2">Administration</a>
            </li>
        <?php endif; ?>
    </ul>
    <div class="col-2 text-end me-3">
        <div class="dropdown">
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <?php $avatar = $_SESSION['utilisateur']['avatar'] ?? null; ?>
                <a href="#" class="d-inline-block text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-3 text-light"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li><a class="dropdown-item text-primary" href="account/accountRead.php"><i class="bi bi-person"></i> Mon compte</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../src/treatment/traitementDeconnexion.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
                </ul>
            <?php else: ?>
                <a href="#" class="d-inline-block text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-3 text-light"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li><a class="dropdown-item text-primary" href="connexion.php"><i class="bi bi-box-arrow-in-right"></i> Connexion</a></li>
                    <li><a class="dropdown-item text-success" href="inscription.php"><i class="bi bi-person-plus"></i> Inscription</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</header>
<section class="container banner bg-dark text-light text-center py-1 mb-3 rounded">
    <h1>Reinitialisation du mot de passe</h1>
</section>
<section class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php
            if (!empty($_SESSION["toastr"])) {
                $type = $_SESSION["toastr"]["type"];
                $message = $_SESSION["toastr"]["message"];
                echo '<script>
                // Set the options that I want
                toastr.options = {
                    "closeButton": true,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-bottom-full-width",
                    "preventDuplicates": true,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "slideDown",
                    "hideMethod": "slideUp"
                }
                toastr.' . $type . '("' . $message . '");


            </script>';
                unset($_SESSION['toastr']);
            }
            ?>
            <form action="../src/treatment/traitementReinitialisation.php" method="post">
                <input type="hidden" name="token" value="<?=$token?>">
                <div class="form-floating mb-3">
                    <input type="password" name="mdp" class="form-control" id="floatingPassword" placeholder="Mot de passe" required autocomplete="current-password">
                    <label for="floatingPassword">Mot de passe</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="confirmation" class="form-control" id="floatingPassword" placeholder="Confirmer le mot de passe" required autocomplete="current-password">
                    <label for="floatingPassword">Confirmation du mot de passe</label>
                </div>
                <div class="row">
                    <button type="submit" class="btn btn-outline-success btn-lg mb-3"><i class="bi bi-box-arrow-in-right"></i> Modifier</button>
                        <a href="connexion.php" class="col btn btn-sm btn-outline-primary me-2"><i class="bi bi-person-plus"></i> Retour à la page de connexion</a>

                </div>
            </form>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>