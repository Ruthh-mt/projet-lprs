
<!doctype html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ACCUEIL • LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">

</head>

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
        <li class="nav-item"><a href="accueil.php" class="btn btn-outline-light active dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="emplois.php" class="btn btn-outline-light me-2">Emplois</a></li>
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
            <a href="inscription.php" class="btn btn-outline-primary">Inscription</a>
        <?php endif; ?>
    </div>
</header>

<?php $ref_offre = $_GET['id'];
 ?>

<form class="card"
          action="../src/treatment/gestionPostuler.php"
          method="POST"
          enctype="multipart/form-data">



        <div class="grid">
            <input type="hidden" name="ref_offre" value="<?php echo htmlspecialchars($ref_offre ?? ''); ?>">

            <div>
                <label class="required" for="email">Adresse e-mail</label>
                <input id="email" name="email" type="email" placeholder="vous@exemple.com" required />
            </div>
        </div>

        <div class="grid-1">
            <div>
                <label class="required" for ="lettre">Lettre de motivation</label>
                <textarea id="lettre_motivation" name="lettre" required></textarea>
            </div>
        </div>

        <div class="grid">
            <div>
                <label for="cv">CV (PDF, DOCX) — optionnel</label>
                <input id="cv" name="cv" type="file" accept=".pdf,.doc,.docx" />
            </div>

        </div>

        <div class="inline" style="margin-top:8px">
            <label><input type="checkbox" name="Consentement" required> J'accepte que mes données soient utilisées pour traiter ma candidature.</label>
        </div>

        <div class="actions">
            <div class="footer-note">Les champs marqués * sont obligatoires.</div>
        </div>
       <div>
           <button type="submit" class="btn btn-primary mt-3">Envoyer la candidature</button>
       </div>
    </form>
</body>
</html>


<style>

    .card {
        border:1px solid rgba(255,255,255,0.08);
        padding:24px;
    }
    label {
        font-weight:600;
        margin-bottom:6px;
        display:block;
    }
    input[type=text],input[type=date],input[type=email],input[type=tel],textarea,select {
        width:100%;
        padding:12px 14px;
        border:1px solid rgba(255,255,255,0.2);
        border-radius:12px;
        background:rgba(255,255,255,0.05);
    }
    textarea { min-height:120px; resize:vertical; }

</style>