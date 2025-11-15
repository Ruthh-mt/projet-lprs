<?php
session_start();
    $page = 'Etudiant';
    require_once '../../src/modele/ModelePostuler.php';
    require_once '../../src/repository/PostulerRepository.php';
    require_once "../../src/bdd/config.php";
    $id_offre = $_GET['id'];
    $postulerRepository = new PostulerRepository();
    $candidaturesPostule = $postulerRepository->findOffreAndUser($_SESSION['utilisateur']['id_user'], $id_offre);


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
</head>
<body>
<header
    class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="../accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://media.tenor.com/ifEkV-aGn3EAAAAi/fat-cat.gif"
                 class="rounded-circle mx-3"
                 style="max-width: 15%; height: auto;">
            <div class="fs-4 text-light text-uppercase">LPRS • ADMIN</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="../accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="../evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="../emplois.php" class="btn btn-outline-light me-2">Emplois</a></li>
        <li class="nav-item"><a href="../forum.php" class="btn btn-outline-light me-2">Forum</a></li>
        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Gestionnaire'): ?>
            <li class="nav-item">
                <a href="../administration.php" class="btn btn-outline-warning active me-2">Administration</a>
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
<nav class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom text-white bg-dark">
    <div class="nav col mb-2 justify-content-center mb-md-0">
        <div class="btn-group mx-1" role="group" aria-label="Basic example">
            <a href="../crudEntreprise/entrepriseRead.php" class="btn btn-outline-info">Entreprise</a>
            <a href="../crudEvenement/evenementRead.php" class="btn btn-outline-danger disabled">Évènement</a>
            <a href="../crudFormation/formationRead.php" class="btn btn-outline-info">Formation</a>
            <a href="../crudGestionnaire/gestionnaireRead.php" class="btn btn-outline-info">Gestionnaire</a>
            <a href="../crudOffre/offreRead.php" class="btn btn-outline-info">Offre</a>
            <a href="../crudPartenaire/partenaireRead.php" class="btn btn-outline-info">Partenaire</a>
            <a href="../crudPost/postRead.php" class="btn btn-outline-danger ">Post</a>
            <a href="../crudReponse/reponseRead.php" class="btn btn-outline-info">Réponses</a>
            <a href="../crudUtilisateur/utilisateurRead.php" class="btn btn-outline-info">Utilisateur</a>
        </div>
    </div>
</nav>

<?php

?>
<!-- SECTION DETAIL -->
<div class="container mb-5">
    <div class="section-offre">
        <div class="offre-header d-flex justify-content-between align-items-center">
            <h2 class="fw-bold"><?= htmlspecialchars($candidaturesPostule['titre']) ?></h2>
            <button type="button" class="btn btn-outline-light" onclick="window.location.href='../evenements.php'">
                <i class="bi bi-arrow-left-circle"></i> Retour
            </button>
        </div>

        <form class="mt-4" action="" method="post">
            <input type="hidden" name="ref_offre" value="<?= htmlspecialchars($id_offre) ?>">
            <input type="hidden" name="refUser" value="<?= htmlspecialchars($_SESSION['utilisateur']['id_user']) ?>">

            <div class="mb-3">
                <label for="motivation" id="motivation" class="form-label">Lettre de motivation</label>
                <textarea type="text" class ="form-control"  name="motivation" ><?= $candidaturesPostule['motivation']?></textarea>
            </div>

            <div class="mb-3">
                <label for="cv" class="form-label">CV (PDF, DOCX) </label>
                <input id="cv" name="cv" type="file" class="form-control" accept=".pdf,.doc,.docx">
            </div>
            <div class="text-center mt-4">
                <form action="candidatureDelete.php" method="post" style="display:inline;">
                    <input type="hidden" name="id_offre" value="<?= $_GET['id'] ?> ">
                    <input type="hidden" name="delete_candidature" value="1">
                    <button type="submit" class="btn btn-sm btn-outline-danger"
                            title="Supprimer" onclick="return confirm('Supprimer cette offre ?')">
                       Supprimer la candidature
                    </button>
                </form>
                <form action="" method="post" style="display:inline;">
                    <input type="hidden" name="update_candidature" value="1">
                    <input type="hidden" name="id_offre" value="<?php $_GET['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-success" >
                        Modifier la candidature
                    </button>
                </form>
            </div>
        </form>



    </div>
</div>

</body>
</html>
<script>
    function autoResize(textarea) {
        textarea.style.height = 'auto'; // Reset height
        textarea.style.height = textarea.scrollHeight + 'px'; // Set to content height
    }

    // Get the textarea
    const ta = document.getElementById('motivation');

    // Adjust height on input
    ta.addEventListener('input', () => autoResize(ta));

    // Adjust height on page load for prefilled content
    window.addEventListener('load', () => autoResize(ta));
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

