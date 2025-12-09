<?php
$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0].'/public';
if (session_status() === PHP_SESSION_NONE) {
    session_start();

    require_once '../../src/repository/FormationRepository.php';

    $formationRepo = new FormationRepository();
    $formations = $formationRepo->findAll(null);
    $selectedId = $selectedId ?? null;

    $page = 'Utilisateur';
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ADMIN • LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
</head>
<body>
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="../accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://i.pinimg.com/originals/a0/50/1e/a0501e0c5659dcfde397299e4234e75a.gif" class="mx-3" style="max-width: 48px;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
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
                    <li><a class="dropdown-item text-primary" href="../account/accountRead.php"><i class="bi bi-person"></i> Mon compte</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../../src/treatment/traitementDeconnexion.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
                </ul>
            <?php else: ?>
                <a href="#" class="d-inline-block text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-3 text-light"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li><a class="dropdown-item text-primary" href="../connexion.php"><i class="bi bi-box-arrow-in-right"></i> Connexion</a></li>
                    <li><a class="dropdown-item text-success" href="../inscription.php"><i class="bi bi-person-plus"></i> Inscription</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</header>
<nav class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom text-white bg-dark">
    <div class="nav col mb-2 justify-content-center mb-md-0">
        <div class="btn-group mx-1" role="group" aria-label="Basic example">
            <a href="../crudEntreprise/entrepriseRead.php" class="btn btn-outline-info">Entreprise</a>
            <a href="../crudEvenement/evenementListe.php" class="btn btn-outline-info">Évènement</a>
            <a href="../crudFormation/formationRead.php" class="btn btn-outline-info">Formation</a>
            <a href="../crudGestionnaire/gestionnaireRead.php" class="btn btn-outline-info">Gestionnaire</a>
            <a href="../crudOffre/offreListe.php" class="btn btn-outline-info">Offre</a>
            <a href="../crudPartenaire/partenaireRead.php" class="btn btn-outline-info">Partenaire</a>
            <a href="../crudPost/postListe.php" class="btn btn-outline-info">Post</a>
            <a href="../crudReponse/reponseRead.php" class="btn btn-outline-info">Réponses</a>
            <a href="../crudUtilisateur/utilisateurRead.php" class="btn btn-outline-info active">Utilisateur</a>
        </div>
        <a href="../crudUtilisateur/utilisateurAValider.php" class="btn btn-outline-warning">A valider</a>
    </div>
</nav>
<section class="container banner bg-info text-white text-center py-1 rounded border">
    <h1>Gestion <?=$page?></h1>
</section>
<section class="container">
    <div action="../../src/treatment/treatmentUserCreate.php" method="post" class="align-self-center"
         enctype="multipart/form-data">
        <div class="row">
            <div class="col form-floating my-2">
                <input type="text" name="prenom" class="form-control" id="floatingPrenom" placeholder="Prénom"
                       autocomplete="given-name" required>
                <label for="floatingPrenom" class="ms-4">Prénom</label>
            </div>

            <div class="col form-floating my-2">
                <input type="text" name="nom" class="form-control" id="floatingNom" placeholder="Nom de famille"
                       autocomplete="family-name" required>
                <label for="floatingNom" class="ms-4">Nom de famille</label>
            </div>
        </div>
        <div class="row">
            <div class="col form-floating my-2">
                <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="Adresse email"
                       autocomplete="email" required>
                <label for="floatingEmail" class="ms-4">Adresse email</label>
            </div>

            <select class="col form-select my-2" aria-label="" id="choix" name="role">
                <option value="">Rôles</option>
                <option value="Étudiant">Étudiant</option>
                <option value="Professeur">Professeur</option>
                <option value="Alumni">Alumni / Ancien élève</option>
                <option value="Partenaire">Partenaire / Entreprise</option>
            </select>
        </div>
        <div class="row">
            <div class="col form-floating my-2">
                <input type="password" name="mdp" class="form-control" id="floatingMdp" placeholder="Mot de passe"
                       autocomplete="new-password" data-toggle-password required>
                <label for="floatingMdp" class="ms-4">Mot de passe</label>
            </div>

            <div class="col form-floating my-2">
                <input type="password" name="confirmation_mot_de_passe" class="form-control" id="floatingMdpConfirm"
                       placeholder="Confirmation du mot de passe" data-toggle-password required>
                <label for="floatingMdpConfirm" class="ms-4">Confirmation du mot de passe</label>
            </div>
        </div>


        <div class="form-check my-2">
            <input class="form-check-input" type="checkbox" value="" id="showPasswords" aria-controls="floatingMdp floatingMdpConfirm">
            <label class="form-check-label" for="showPasswords">
                Afficher les mots de passe
            </label>
        </div>

        <div id="extraFields"></div>

        <div class="d-grid gap-2 my-2 ">
            <button class="btn btn-outline-success" type="submit">AJOUTER</button>
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
                <div class="form-floating my-2">
                    <select class="form-select" name="classe" id="floatingClasse" aria-label="Choix de la formation" required>
                        <?php foreach ($formations as $f):
                    $id = $f->id_formation;
                    $nom = $f->nom;
                    $sel = ($selectedId !== null && (int)$selectedId === (int)$id) ? ' selected' : '';
                    ?>
                    <option value="<?= htmlspecialchars($id) ?>"<?= $sel ?>><?= htmlspecialchars($nom) ?></option>
                    <?php endforeach; ?>
                    </select>
                    <label for="floatingClasse">Formation / Classe</label>
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
</section>
