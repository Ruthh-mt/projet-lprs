<?php
require_once "../../src/modele/ModeleEvenement.php";
require_once "../../src/modele/ModeleOffre.php";
require_once "../../src/repository/OffreRepository.php";
require_once "../../src/repository/FicheEntrepriseRepository.php";

require_once "../../src/bdd/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('Aucune offre sélectionné'); window.location.href='../evenements.php';</script>";
    exit;
}
$offreRepo = new OffreRepository();
$offre = $offreRepo->getOffreById($id);
$ficheRepo = new FicheEntrepriseRepository();
$fiche = $ficheRepo->findFicheByOffre($id);
$nom_entreprise = $fiche->nom_entreprise;

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détail d’un évènement • LPRS</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>
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
        <a href="../evenements.php" class="btn btn-outline-light">Retour aux offres</a>
    </div>
</header>
<!-- SECTION DETAIL -->
<div class="container mb-5">
    <div class="section-offre">
        <div class="offre-header d-flex justify-content-between align-items-center">
            <h2 class="fw-bold"><?= htmlspecialchars($offre->titre) ?></h2>

            <button type="button" class="btn btn-outline-light" onclick="window.location.href='../profil.php'">
                <i class="bi bi-arrow-left-circle"></i> Retour
            </button>
        </div>
        <?php if (!empty($_SESSION["toastr"])) {
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
        <form class="mt-4" action="../../src/treatment/traitementInscriptionEvenement.php" method="post">
            <input type="hidden" name="ref_eve" value="<?= htmlspecialchars($offre->id_offre) ?>">
            <input type="hidden" name="refUser" value="<?php if (isset($_SESSION['utilisateur'])) {
                echo htmlspecialchars($_SESSION['utilisateur']['id_user']);
            } ?>">

            <div class="mb-3">
                <label for="type_eve">Type d’offre</label>
                <input type="text" readonly class="form-control" id="type_eve"
                       value="<?= htmlspecialchars($offre->type) ?>">
            </div>
            <div class="mb-3">
                <label for="status">Status de l'offre</label>
                <input type="text" readonly class="form-control" id="status"
                       value="<?= htmlspecialchars($offre->etat) ?>">
            </div>

            <div class="mb-3">
                <label for="mission">Mission</label>
                <textarea readonly class="form-control"
                          id="desc_eve"><?= htmlspecialchars($offre->mission) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="description">Description</label>
                <textarea readonly class="form-control"
                          id="description"><?= htmlspecialchars($offre->description) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="element_eve">Salaire</label>
                <input type="text" readonly class="form-control" id="element_eve"
                       value="<?= htmlspecialchars($offre->salaire) ?>">
            </div>

            <div class="mb-3">
                <label for="nb_place">Entreprise</label>

                    <input type="text" readonly class="form-control" id="entreprise"
                           value="<?= htmlspecialchars($nom_entreprise) ?>">

            </div>
            <a href="../crudOffre/offreUpdate.php?id=<?= $offre->id_offre ?>"
               class="btn btn-sm btn-outline-primary me-1" title="Modifier">
               Modifier l'offre</i>
            </a>
            <form action="../../src/treatment/traitementDeleteOffre.php"
                  method="post" class="d-inline">
                <input type="hidden" name="id_offre" value="<?= (int)$offre->id_offre ?>">
                <input type="hidden" name="delete_offre" value="1">
                <button type="submit" class="btn btn-sm btn-outline-danger"
                        title="Supprimer"
                        onclick="return confirm('Supprimer cette offre ?')">
                    Supprimer l'offre
                </button>
            </form>        </form>


</body>
</html>