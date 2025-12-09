<?php
$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0].'/public';
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
<body class="bg-success-subtle">
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://media.tenor.com/YzarFuImKCIAAAAj/blob-derpy.gif" class="mx-3" style="max-width: 48px;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
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
                    <?php if ($avatar): ?>
                        <img src="<?= $prefix.htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil" class="rounded-circle" style="max-width: 48px;object-fit:cover;">
                    <?php else: ?>
                        <i class="bi bi-person-circle fs-3 text-light"></i>
                    <?php endif; ?>
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
    <h1>Inscription</h1>
</section>
<section class="row">
    <div class="col">
    </div>
    <div class="col">
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
                <button class="btn btn-outline-success" type="submit"><i class="bi bi-person-plus"></i> S'inscrire</button>
                <a class="btn btn-outline-primary" href="connexion.php" role="button"><i class="bi bi-box-arrow-in-right"></i> Se connecter</a>
            </div>
        </form>
    </div>
    <div class="col">
    </div>
    <script>
        // Fonction pour charger les formations depuis l'API
        async function loadFormations() {
            try {
                const response = await fetch('../api/get_formations.php');
                if (!response.ok) {
                    throw new Error('Erreur lors du chargement des formations');
                }
                return await response.json();
            } catch (error) {
                console.error('Erreur:', error);
                // Retourner une liste vide en cas d'erreur
                return [];
            }
        }

        // Fonction pour générer les options de formation
        async function generateFormationOptions() {
            const formations = await loadFormations();
            let options = '<option value="">Sélectionnez une formation</option>';
            
            formations.forEach(formation => {
                options += `<option value="${formation.nom}">${formation.nom}</option>`;
            });
            
            return `
                <div class="form-selectfloating my-2">
                    <select class="form-select" name="classe" id="floatingClasse" required>
                        ${options}
                    </select>
                </div>`;
        }

        document.addEventListener("DOMContentLoaded", async () => {
            const extraFields = document.getElementById("extraFields");
            const selectRole = document.getElementById("choix");

            selectRole.addEventListener("change", async () => {
                extraFields.innerHTML = "";

                if (selectRole.value === "Étudiant") {
                    const formationOptions = await generateFormationOptions();
                    extraFields.innerHTML = formationOptions;
                    extraFields.innerHTML += `
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
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>