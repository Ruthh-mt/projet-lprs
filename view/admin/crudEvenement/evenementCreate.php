<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$idUser = $_SESSION['utilisateur']["id_user"];
$role = $_SESSION['utilisateur']["role"];
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Création d’un évènement • LPRS</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
        <a href="../../accueil.php" class="d-inline-flex align-items-center text-decoration-none">
            <img src="https://media.tenor.com/1DV7nkfj5OkAAAAM/blobgodeto-blobdance.gif"
                 class="rounded-circle mx-3"
                 style="max-width: 15%; height: auto;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>

    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="../../accueil.php" class="btn btn-outline-light me-2">Accueil</a></li>
        <li class="nav-item"><a href="../../evenements.php" class="btn btn-outline-light active me-2">Évènements</a></li>
        <li class="nav-item"><a href="../../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="../../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="../../emplois.php" class="btn btn-outline-light me-2">Emplois</a></li>
        <?php if ($role === 'Gestionnaire'): ?>
            <li class="nav-item">
                <a href="../../administration.php" class="btn btn-outline-warning me-2">Administration</a>
            </li>
        <?php endif; ?>
    </ul>

    <div class="col-2 btn-group md-3 me-3 text-end">
        <a href="../../account/accountRead.php" class="btn btn-outline-primary">Mon compte</a>
        <a href="../../../src/treatment/traitementDeconnexion.php" class="btn btn-outline-danger">Déconnexion</a>
    </div>
</header>


<section class="container banner bg-danger text-warning text-center py-1 rounded border">
    <h1>Cette page est censé être pour le gestionnaire</h1>
</section>


<div class="container mb-5">
    <div class="section-offre">
        <div class="offre-header d-flex justify-content-between align-items-center">
            <h2 class="fw-bold">Création d’un évènement</h2>
            <button type="button" class="btn btn-outline-light" onclick="window.location.href='../../evenements.php'">
                <i class="bi bi-arrow-left-circle"></i> Retour
            </button>
        </div>

        <!-- Formulaire -->
        <form class="mt-4" action="../../../src/treatment/traitementAjoutEvenement.php" method="post">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <input type="hidden" name="id_user" value="<?= htmlspecialchars($idUser) ?>">
            <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">

            <div class="mb-3">
                <label for="titre_eve">Titre de l’évènement</label>
                <input class="form-control" type="text" id="titre_eve" name="titre_eve" placeholder="Entrez le titre de l’évènement" required>
            </div>

            <div class="mb-3">
                <label for="type_eve">Type de l’évènement</label>
                <input class="form-control" type="text" id="type_eve" name="type_eve" placeholder="Ex : Conférence, Atelier..." required>
            </div>

            <div class="mb-3">
                <label for="status">Status de l'evenement</label>
                <select class="form-select" aria-label="Default select example"  name="status" required>
                    <option selected>Choisissez le status</option>
                    <option value="en attente">En attente de validation par un prof</option>
                    <?php
                    if($_SESSION["utilisateur"]['role']!="Étudiant" ){
                        echo'<option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>';
                    }
                    ?>
                </select>

            </div>

            <div class="mb-3">
                <label for="desc_eve">Description</label>
                <textarea class="form-control" id="desc_eve" name="desc_eve" placeholder="Entrez la description de l’évènement" required></textarea>
            </div>

            <div class="mb-3">
                <label for="lieu_eve">Lieu</label>
                <input class="form-control" type="text" id="lieu_eve" name="lieu_eve" placeholder="Ex : Salle 203 ou Campus principal" required>
            </div>

            <div class="mb-3">
                <label for="element_eve">Éléments nécessaires</label>
                <textarea class="form-control" id="element_eve" name="element_eve" placeholder="Liste du matériel ou documents nécessaires"></textarea>
            </div>

            <div class="mb-3">
                <label for="nb_plc">Nombre de places disponibles</label>
                <input class="form-control" type="number" id="nb_plc" name="nb_plc" placeholder="Entrez le nombre de places disponibles" required>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit">Valider</button>
                <button class="btn btn-secondary" type="reset">Annuler</button>
                <a href="../../evenements.php" class="btn btn-outline-dark">Retour</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
