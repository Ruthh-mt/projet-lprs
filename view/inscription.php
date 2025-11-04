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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
</head>
<body>
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
        <li class="nav-item"><a href="accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Gestionnaire'): ?>
            <li class="nav-item">
                <a href="administration.php" class="btn btn-outline-warning me-2">Administration</a>
            </li>
        <?php endif; ?>
    </ul>
    <div class="col-2 btn-group md-3 me-3 text-end" role="group" aria-label="Boutons utilisateur">
        <?php if (isset($_SESSION['utilisateur'])): ?>
            <a href="account/accountRead.php" class="btn btn-outline-primary">Mon compte</a>
            <a href="../src/treatment/traitementDeconnexion.php" class="btn btn-outline-danger">Déconnexion</a>
        <?php else: ?>
            <a href="connexion.php" class="btn btn-outline-success">Connexion</a>
            <a href="inscription.php" class="btn btn-outline-primary active">Inscription</a>
        <?php endif; ?>
    </div>
</header>

<div class="row">
    <div class="col">
    </div>
    <div class="col">
        <h4 class="text-center">INSCRIPTION</h4>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <form action="../src/treatment/traitementInscription.php" method="post" class="align-self-center"
              enctype="multipart/form-data">
            <div class="form-floating my-2">
                <input type="text" name="prenom" class="form-control" id="floatingPrenom" placeholder="Prénom"
                       autocomplete="given-name" required>
                <label for="floatingPrenom">Prénom</label>
            </div>

            <div class="form-floating my-2">
                <input type="text" name="nom" class="form-control" id="floatingNom" placeholder="Nom de famille"
                       autocomplete="family-name" required>
                <label for="floatingNom">Nom de famille</label>
            </div>

            <div class="form-floating my-2">
                <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="Adresse email"
                       autocomplete="email" required>
                <label for="floatingEmail">Adresse email</label>
            </div>

            <div class="form-floating my-2">
                <!-- data-toggle-password pour cibler facilement les champs à afficher/masquer -->
                <input type="password" name="mdp" class="form-control" id="floatingMdp" placeholder="Mot de passe"
                       autocomplete="new-password" data-toggle-password required>
                <label for="floatingMdp">Mot de passe</label>
            </div>

            <div class="form-floating my-2">
                <input type="password" name="confirmation_mot_de_passe" class="form-control" id="floatingMdpConfirm"
                       placeholder="Confirmation du mot de passe" data-toggle-password required>
                <label for="floatingMdpConfirm">Confirmation du mot de passe</label>
            </div>

            <div class="form-check my-2">
                <input class="form-check-input" type="checkbox" value="" id="showPasswords" aria-controls="floatingMdp floatingMdpConfirm">
                <label class="form-check-label" for="showPasswords">
                    Afficher les mots de passe
                </label>
            </div>

            <select class="form-select my-2" aria-label="Default select example" id="choix" name="role">
                <option value="">Rôles</option>
                <option value="Étudiant">Étudiant</option>
                <option value="Professeur">Professeur</option>
                <option value="Alumni">Alumni / Ancien élève</option>
                <option value="Partenaire">Partenaire / Entreprise</option>
            </select>

            <div id="extraFields"></div>

            <div class="d-grid gap-2 my-2 ">
                <button class="btn btn-outline-success" type="submit">S'INSCRIRE</button>
                <a class="btn btn-outline-primary" href="connexion.php" role="button">SE CONNECTER</a>
            </div>
        </form>
    </div>
    <div class="col">
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const extraFields = document.getElementById("extraFields");
            const selectRole = document.getElementById("choix");

            selectRole.addEventListener("change", () => {
                extraFields.innerHTML = "";

                if (selectRole.value === "Étudiant") {
                    extraFields.innerHTML = `
                 <div class="form-selectfloating my-2">
                    <select class="form-select" name="classe"   id="floatingClasse">
                        <option value="3° Pro">Troisième Pro</option>
                        <option value="Bac TRPM">Bac Pro TRPM</option>
                        <option value="BAC MSPC">Bac Pro MSPC</option>
                        <option value="BAC CIEL">Bac Pro CIEL</option>
                        <option value="Formation SST">Formation SST</option>
                        <option value="BAC STI2D">Bac STI2D</option>
                        <option value="BTS CPRP">BTS CPRP</option>
                        <option value="BTS SIO SISR">BTS SIO SISR</option>
                        <option value="BTS SIO SLAM">BTS SIO SLAM</option>
                        <option value="BTS MS">BTS MS</option>
                        <option value="BTS CQPM">BTS CQPM</option>
                    </select>
                </div>
                <div class="form-floating my-2">
                    <input type="number" name="annee_promo" class="form-control" id="floatingAnnee" placeholder="Année de promotion" min="1900" max="2100" step="1">
                    <label for="floatingAnnee">Année de promotion</label>
               </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">Curriculum Vitae</label>
                    <input class="form-control" type="file" id="formFile" name="cv" accept="application/pdf">
               </div>
            `;
                } else if (selectRole.value === "Alumni") {
                    extraFields.innerHTML = `
                <div class="form-floating my-2">
                    <input type="number" name="annee_promo" class="form-control" id="floatingAnnee" placeholder="Année de promotion" min="1900" max="2100" step="1" required>
                    <label for="floatingAnnee">Année de promotion</label>
               </div>
                <div class="form-floating my-2">
                    <input type="text" name="poste" class="form-control" id="floatingPoste" placeholder="Poste actuel">
                    <label for="floatingPoste">Poste actuel</label>
               </div>
               <div class="mb-3">
                    <label for="formFile" class="form-label">Curriculum Vitae</label>
                    <input class="form-control" type="file" id="formFile" name="cv" accept="application/pdf">
               </div>
            `;
                } else if (selectRole.value === "Professeur") {
                    extraFields.innerHTML = `
                <div class="form-floating my-2">
                    <input type="text" name="specialite" class="form-control" id="floatingSpe" placeholder="Matière enseignée" required>
                    <label for="floatingSpe">Matière enseignée</label>
                </div>
            `;
                } else if (selectRole.value === "Partenaire") {
                    extraFields.innerHTML = `
                <div class="form-floating my-2">
                    <input type="text" name="poste" class="form-control" id="floatingPoste" placeholder="Poste occupé" required>
                    <label for="floatingPoste">Poste occupé</label>
                </div>
                <div class="form-floating my-2">
                    <input type="text" name="raison" class="form-control" id="floatingRaison" placeholder="Raison de l'inscription" required>
                    <label for="floatingRaison">Raison de l'inscription</label>
                </div>
            `;
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const checkbox = document.getElementById('showPasswords');
            const pwdFields = document.querySelectorAll('input[data-toggle-password]');

            checkbox.addEventListener('change', function () {
                const show = checkbox.checked;
                pwdFields.forEach(function (f) {
                    try {
                        f.type = show ? 'text' : 'password';
                    } catch (e) {
                        const newInput = f.cloneNode(true);
                        newInput.type = show ? 'text' : 'password';
                        f.parentNode.replaceChild(newInput, f);
                    }
                });
            });
        });
    </script>
</div>