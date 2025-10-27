<?php
require_once "../../src/modele/Evenement.php";
require_once "../../src/modele/EvenementUser.php";
require_once "../../src/repository/EvenementRepository.php";
require_once "../../src/repository/EvenementUserRepository.php";
require_once "../../src/bdd/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('Aucun événement sélectionné'); window.location.href='../evenements.php';</script>";
    exit;
}

$evenementRepo = new EvenementRepository();
$evenement = $evenementRepo->getAnEvenement(new Evenement(["idEvenement" => $id]));
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détail d’un évènement • LPRS</title>

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
            margin-bottom: 6px;
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
            min-height: 120px;
            resize: vertical;
        }
    </style>
</head>
<body>

<header class="d-flex flex-wrap align-items-center justify-content-between py-3 mb-4 border-bottom bg-dark px-4">
    <div class="d-flex align-items-center">
        <a href="../accueil.php" class="d-inline-flex text-decoration-none align-items-center">
            <img src="https://media.tenor.com/1DV7nkfj5OkAAAAM/blobgodeto-blobdance.gif"
                 class="rounded-circle mx-3"
                 style="max-width: 40px; height: auto;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <div>
        <a href="../evenements.php" class="btn btn-outline-light">Retour aux évènements</a>
    </div>
</header>

<!-- SECTION DETAIL -->
<div class="container mb-5">
    <div class="section-offre">
        <div class="offre-header d-flex justify-content-between align-items-center">
            <h2 class="fw-bold"><?= htmlspecialchars($evenement->getTitreEvenement()) ?></h2>
            <button type="button" class="btn btn-outline-light" onclick="window.location.href='../evenements.php'">
                <i class="bi bi-arrow-left-circle"></i> Retour
            </button>
        </div>

        <form class="mt-4" action="../../src/treatment/traitementInscriptionEvenement.php" method="post">
            <input type="hidden" name="ref_eve" value="<?= htmlspecialchars($evenement->getIdEvenement()) ?>">
            <input type="hidden" name="refUser" value="<?= htmlspecialchars($_SESSION['utilisateur']['id_user']) ?>">

            <div class="mb-3">
                <label for="type_eve">Type d’évènement</label>
                <input type="text" readonly class="form-control" id="type_eve"
                       value="<?= htmlspecialchars($evenement->getTypeEvenement()) ?>">
            </div>

            <div class="mb-3">
                <label for="lieu_eve">Lieu</label>
                <input type="text" readonly class="form-control" id="lieu_eve"
                       value="<?= htmlspecialchars($evenement->getLieuEvenement()) ?>">
            </div>

            <div class="mb-3">
                <label for="desc_eve">Description</label>
                <textarea readonly class="form-control" id="desc_eve"><?= htmlspecialchars($evenement->getDescEvenement()) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="element_eve">Éléments nécessaires</label>
                <input type="text" readonly class="form-control" id="element_eve"
                       value="<?= htmlspecialchars($evenement->getElementEvenement()) ?>">
            </div>

            <div class="mb-3">
                <label for="nb_place">Places disponibles</label>
                <input type="number" readonly class="form-control" id="nb_place"
                       value="<?= htmlspecialchars($evenement->getNbPlace()) ?>">
            </div>

            <?php
            $evenementUserRepository = new EvenementUserRepository();
            $superviseur = $evenementUserRepository->getSuperviseur($evenement->getIdEvenement());
            $eveUser = new EvenementUser(["refUser" => $_SESSION['utilisateur']['id_user']]);
            $estInscrit = $evenementUserRepository->verifDejaInscritEvenement($eveUser);
            ?>

            <div class="text-center mt-4">
                <?php if ($_SESSION['utilisateur']['id_user'] != $superviseur->getRefUser()): ?>
                    <?php if (!$estInscrit): ?>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-person-plus"></i> Participer
                        </button>
                    <?php else: ?>
                        <button class="btn btn-danger" type="submit">
                            <i class="bi bi-person-dash"></i> Se désinscrire
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </form>

        <?php if ($_SESSION['utilisateur']['id_user'] == $superviseur->getRefUser()): ?>
            <div class="text-center mt-3">
                <a href="evenementUpdate.php?id=<?= $evenement->getIdEvenement() ?>" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i> Modifier l’évènement
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
