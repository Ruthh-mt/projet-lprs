<?php
require_once "../../src/modele/ModeleEvenement.php";
require_once "../../src/modele/ModeleEvenementUser.php";
require_once "../../src/repository/EvenementRepository.php";
require_once "../../src/repository/EvenementUserRepository.php";
require_once "../../src/bdd/config.php";

session_start();

$id = $_GET['id'] ?? null;
if (!isset($_GET['id'])) {
    echo "<script>alert('Aucun évènement sélectionné'); window.location.href='../evenements.php';</script>";
    exit;
}

$evenementRepo = new EvenementRepository();
$evenement = $evenementRepo->getAnEvenement(new ModeleEvenement(["idEvenement" => $id]));
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier un évènement • LPRS</title>

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
<header class="d-flex flex-wrap align-items-center justify-content-between py-3 mb-4 border-bottom bg-dark px-4">
    <div class="d-flex align-items-center">
        <a href="../accueil.php" class="d-inline-flex align-items-center text-decoration-none">
            <img src="https://media.tenor.com/1DV7nkfj5OkAAAAM/blobgodeto-blobdance.gif"
                 class="rounded-circle mx-3"
                 style="max-width: 40px; height: auto;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <div>
        <a href="../evenements.php" class="btn btn-outline-light">
            <i class="bi bi-arrow-left-circle"></i> Retour
        </a>
    </div>
</header>

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
<!-- SECTION FORMULAIRE -->
<div class="container mb-5">
    <div class="section-offre">

        <div class="offre-header d-flex justify-content-between align-items-center">
            <h2 class="fw-bold">Modifier un évènement</h2>
        </div>

        <form class="mt-4" action="../../src/treatment/traitementUpdateEvenement.php" method="post">

            <input type="hidden" name="refEve" value="<?= htmlspecialchars($evenement->id_evenement) ?>">
            <input type="hidden" name="refUser" value="<?= htmlspecialchars($_SESSION['utilisateur']['id_user']) ?>">

            <div class="mb-3">
                <label for="titreEve">Titre de l’évènement</label>
                <input class="form-control" type="text" id="titreEve" name="titreEve"
                       value="<?= htmlspecialchars($evenement->titre_eve) ?>" required>
            </div>

            <div class="mb-3">
                <label for="typeEve">Type</label>
                <input class="form-control" type="text" id="typeEve" name="typeEve"
                       value="<?= htmlspecialchars($evenement->type_eve) ?>" required>
            </div>
            <div class="mb-3">
                <label for="status">Status</label>
                <select class="form-select" id="status" name="status">

                    <?php //if($_SESSION["utilisateur"]['role']!="Étudiant"|| $evenement->est_valide===1 ):?>
                    <?php $options = ["en attente", "actif", "inactif"];
                    for ($i = 0; $i < count($options); $i++) {
                        if ($evenement->status == $options[$i]) {
                            echo '<option selected value="' . $options[$i] . '">' . $options[$i] . '</option>';
                        } else {
                            echo '<option value="' . $options[$i] . '">' . $options[$i] . '</option>';
                        }
                    }
                    ?>

                </select>
            </div>
            <div class="mb-3">
                <label for="dateHeureEvenement" class="form-label">Date et heure de l'evenement</label>
                <input type="datetime-local" class="form-control" id="dateHeureEvenement" name=" dateHeureEvenement" value="<?= htmlspecialchars($evenement->date_heure_evenement)?>">
            </div>
            <div class="mb-3">
                <label for="lieuEve">Lieu</label>
                <input class="form-control" type="text" id="lieuEve" name="lieuEve"
                       value="<?= htmlspecialchars($evenement->lieu_eve) ?>" required>
            </div>

            <div class="mb-3">
                <label for="descEve">Description</label>
                <textarea class="form-control" id="descEve" name="descEve"
                          rows="5"><?= htmlspecialchars($evenement->desc_eve) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="elementEve">Éléments nécessaires</label>
                <input class="form-control" type="text" id="elementEve" name="elementEve"
                       value="<?= htmlspecialchars($evenement->element_eve) ?>">
            </div>

            <div class="mb-3">
                <label for="nbPlace">Nombre de places disponibles</label>
                <input class="form-control" type="number" id="nbPlace" name="nbPlace"
                       value="<?= htmlspecialchars($evenement->nb_place) ?>" required>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <div>
                    <a href="../evenements.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i>
                    </a>

                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#confirmModal">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-floppy"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODALE DE CONFIRMATION -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer cet évènement ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="post" action="../../src/treatment/traitementDeleteEvenement.php">
                    <input type="hidden" name="idevenement" value="<?= htmlspecialchars($evenement->id_evenement) ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-exclamation-octagon"></i> Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
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
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
