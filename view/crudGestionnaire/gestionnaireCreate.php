<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = 'Gestionnaire';

// Inclure le repository des utilisateurs
require_once __DIR__ . '/../../src/repository/UtilisateurRepository.php';
$utilisateurRepo = new UtilisateurRepository();
$utilisateurs = $utilisateurRepo->findNonGestionnaires();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des Gestionnaires • LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
</head>
<body>
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="../../accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://media.tenor.com/ifEkV-aGn3EAAAAi/fat-cat.gif"
                 class="rounded-circle mx-3"
                 style="max-width: 15%; height: auto;">
            <div class="fs-4 text-light text-uppercase">LPRS • ADMIN</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="../../accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="../../evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="../../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="../../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="../../emplois.php" class="btn btn-outline-light me-2">Emplois</a></li>
        <li class="nav-item"><a href="../../forum.php" class="btn btn-outline-light me-2">Forum</a></li>
        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Gestionnaire'): ?>
            <li class="nav-item">
                <a href="../../administration.php" class="btn btn-outline-warning active me-2">Administration</a>
            </li>
        <?php endif; ?>
    </ul>
    <div class="col-2 btn-group md-3 me-3 text-end" role="group" aria-label="Boutons utilisateur">
        <?php if (isset($_SESSION['utilisateur'])): ?>
            <a href="../../account/accountRead.php" class="btn btn-outline-primary">Mon compte</a>
            <a href="../../src/treatment/traitementDeconnexion.php" class="btn btn-outline-danger">Déconnexion</a>
        <?php else: ?>
            <a href="../../connexion.php" class="btn btn-outline-success">Connexion</a>
            <a href="../../inscription.php" class="btn btn-outline-primary">Inscription</a>
        <?php endif; ?>
    </div>
</header>
<nav class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom text-white bg-dark">
    <div class="nav col mb-2 justify-content-center mb-md-0">
        <div class="btn-group mx-1" role="group" aria-label="Basic example">
            <a href="../crudEntreprise/entrepriseRead.php" class="btn btn-outline-info">Entreprise</a>
            <a href="../crudEvenement/evenementRead.php" class="btn btn-outline-danger disabled">Évènement</a>
            <a href="../crudFormation/formationRead.php" class="btn btn-outline-info">Formation</a>
            <a href="../crudGestionnaire/gestionnaireRead.php" class="btn btn-outline-info">Gestionnaire</a>
            <a href="../crudOffre/offreRead.php" class="btn btn-outline-info">Offre</a>
            <a href="../crudPartenaire/partenaireRead.php" class="btn btn-outline-info">Partenaire</a>
            <a href="../crudPost/postRead.php" class="btn btn-outline-danger disabled">Post</a>
            <a href="../crudReponse/reponseRead.php" class="btn btn-outline-info">Réponses</a>
            <a href="../crudUtilisateur/utilisateurRead.php" class="btn btn-outline-info">Utilisateur</a>
        </div>
    </div>
</nav>
<section class="container banner bg-info text-white text-center py-1 rounded border">
    <h1>Gestion <?=$page?></h1>
</section>
<section class="container">
    <form action="../../src/treatment/treatmentGestionnaireCreate.php" method="post" class="align-self-center" enctype="multipart/form-data">
        <div class="row">
            <div class="col-12 my-3">
                <div class="card my-3">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0">Sélectionner un utilisateur existant</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="utilisateur_id" class="form-label">Utilisateur à promouvoir</label>
                            <select class="form-select" id="utilisateur_id" name="utilisateur_id">
                                <option value="">-- Sélectionnez un utilisateur --</option>
                                <?php foreach ($utilisateurs as $utilisateur): ?>
                                    <option value="<?= htmlspecialchars($utilisateur['id_user']) ?>">
                                        <?= htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom'] . ' (' . $utilisateur['email'] . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Sélectionnez un utilisateur existant à promouvoir en tant que gestionnaire.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-grid gap-2 my-2">
            <button class="btn btn-outline-success" type="submit">AJOUTER</button>
        </div>
    </form>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Gestion de l'affichage/masquage des mots de passe
        const showPasswords = document.getElementById('showPasswords');
        const passwordFields = document.querySelectorAll('[data-toggle-password]');
        
        showPasswords.addEventListener('change', (e) => {
            const type = e.target.checked ? 'text' : 'password';
            passwordFields.forEach(field => {
                field.type = type;
            });
        });
    });
</script>

<!-- Scripts Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>