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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
                margin-top: 10px;
                display: block;
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
                resize: vertical;
                min-height: 120px;
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
    <main>
        <div class="container mb-5">
            <div class="section-offre">
                <div class="offre-header d-flex justify-content-between align-items-center">
                    <h2 class="fw-bold">Création d’un Post</h2>
                    <button type="button" class="btn btn-outline-light" onclick="window.location.href='../forum.php'">
                        <i class="bi bi-arrow-left-circle"></i> Retour
                    </button>
                </div>

<?php if(empty($_SESSION['utilisateur'])):?>
    echo'<h5> Vous etes pas connecté. Veuillez vous connecter</h5>
<a  class="btn btn-secondary" href="../connexion.php" role="button">Se connecter</a>
<p>Erreur : Identify yourself who are you</p>';

<?php else : ?>
<form action="../../src/treatment/traitementAjoutPost.php" method="post">
    <?php if(!empty($_SESSION["toastr"])){
        $type=$_SESSION["toastr"]["type"];
        $message=$_SESSION["toastr"]["message"];
        echo'<script>
            // Set the options that I want
            toastr.options = {
                "closeButton": true,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-full-width",
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
            toastr.'.$type.'("'.$message.'");


        </script>';
        unset($_SESSION['toastr']);
    }
    ?>
        <input type="hidden" id="id_user" name="ref_user" value="<?=$idUser; ?>">
    <div class="mb-3">
        <label for="titre_post"> Titre du Post</label>
        <input class="form-control" type="text" id="titre_post" name="titre_post" placeholder="Entrez le titre du Post">
    </div>
    <div class="mb-3">
        <label for="contenu_post">Contenue du Post</label>
        <textarea class="form-control" type="text" id="contenu_post" name="contenu_post" placeholder="Entrez le contenue du Post"></textarea>
    </div>
    <!--
ajouter une fonctionalité pour les canal en fonction des roles
Un petit select qui si le role est [insert role] alors on affiche le canal [insert canal]-->
    <div class="mb-3">
    <label for="canal">Canal de discussion</label>
        <select class="form-select" aria-label="Default select example"  name="canal" required>
            <option selected>Choisiser un canal</option>
            <option value="general">Canal General</option>
            <?php if($_SESSION["utilisateur"]['role']==="Étudiant" || $_SESSION["utilisateur"]['role']==="Professeur" ):?>
                <option value="profediant">Canal Etudiant/Professeur</option>
            <?php elseif ($_SESSION["utilisateur"]['role']==="Alumni" || $_SESSION["utilisateur"]['role']==="Partenaire" ):?>
                    <option value="entrumnis">Canal Alumni/Entreprise</option>
            <?php elseif ($_SESSION["utilisateur"]['role']=== 'Gestionnaire' ):?>
                <option value="admin">Canal Gestionnaire</option>

            <?php endif; ?>
        </select>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-primary" type="submit">Valider</button>
        <button class="btn btn-secondary" type="reset">Annuler</button>
        <a href="../forum.php" class="btn btn-outline-dark">Retour</a>
    </div>
    </form>


        <?php endif;?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script>
            function autoResize(textarea) {
                textarea.style.height = 'auto'; // Reset height
                textarea.style.height = textarea.scrollHeight + 'px'; // Set to content height
            }

            // Get the textarea
            const ta = document.getElementById('desc_eve');

            // Adjust height on input
            ta.addEventListener('input', () => autoResize(ta));

            // Adjust height on page load for prefilled content
            window.addEventListener('load', () => autoResize(ta));
        </script
</body>
</html>
