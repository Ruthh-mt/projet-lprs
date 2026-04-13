<?php
if (session_status() === PHP_SESSION_NONE) {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<body class="bg-primary-subtle">
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://media.tenor.com/YzarFuImKCIAAAAj/blob-derpy.gif" class="mx-3" style="max-width: 48px;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <div class="col-2 text-end me-3">
        <div class="dropdown">
            <a href="#" class="d-inline-block text-decoration-none dropdown-toggle"
               data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-3 text-light"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end text-small">
                <li><a class="dropdown-item text-primary" href="connexion.php"><i class="bi bi-box-arrow-in-right"></i> Connexion</a></li>
                <li><a class="dropdown-item text-success" href="inscription.php"><i class="bi bi-person-plus"></i> Inscription</a></li>
            </ul>
        </div>
    </div>
</header>
<section class="container banner bg-dark text-light text-center py-1 mb-3 rounded">
    <h1>Mot de passe oublié</h1>
</section>
<section class="container my-5">
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
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="../src/treatment/traitementMdpOublie.php" method="post">
                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="Adresse email" required autocomplete="email">
                    <label for="floatingEmail">Adresse email</label>
                </div>
                <div class="row">
                    <button type="submit" class="btn btn-outline-success btn-lg mb-3"><i class="bi bi-envelope-at-fill"></i> Envoyer le mail </button>
                    <a href="connexion.php" class="col btn btn-sm btn-outline-primary me-2"><i class="bi bi-arrow-left-circle"></i> Retour à la connexion</a>
                </div>
            </form>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>