<?php
require_once "../../src/modele/ModeleEvenement.php";
require_once "../../src/modele/ModeleEvenementUser.php";
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
$evenementUserRepository = new EvenementUserRepository();
$evenement = $evenementRepo->getAnEvenement(new ModeleEvenement(["idEvenement" => $id]));
$nbInscrits=$evenementUserRepository->countAllInscritsByEvenement($id);

$superviseurs = $evenementUserRepository->getSuperviseur($evenement->id_evenement);
$eveUser = new ModeleEvenementUser(["refUser" => $_SESSION['utilisateur']['id_user'],"refEvenement"=>$evenement->id_evenement]);
$estInscrit = $evenementUserRepository->verifDejaInscritEvenement($eveUser);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détail d’un évènement • LPRS</title>

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

<!--- si l'utilisateur n'est pas connecter on peut lui afficher la page mais il faut rajouter un modal pous si il veut s'inscrire il
//faudrat se connecter --->

<!-- SECTION DETAIL -->
<div class="container mb-5">
    <div class="section-offre">
        <div class="offre-header d-flex justify-content-between align-items-center">
            <h2 class="fw-bold"><?= htmlspecialchars($evenement->titre_eve) ?></h2>
            <?php if(in_array($_SESSION['utilisateur']['id_user'],$superviseurs,true)):?>
            <button type="button" class="btn btn-outline-light" onclick="window.location.href='evenementValidateUser.php?id=<?=$id?>'">
                <i class="bi bi-person-gear"></i>Voir la liste des inscrits
            </button>
            <?php endif; ?>
            <button type="button" class="btn btn-outline-light" onclick="window.location.href='../evenements.php'">
                <i class="bi bi-arrow-left-circle"></i> Retour
            </button>
        </div>
        <?php if(!empty($_SESSION["toastr"])){
        $type=$_SESSION["toastr"]["type"];
        $message=$_SESSION["toastr"]["message"];
        echo'<script>
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
            toastr.'.$type.'("'.$message.'");


        </script>';
        unset($_SESSION['toastr']);
        }
        ?>
        <form class="mt-4" action="../../src/treatment/traitementInscriptionEvenement.php" method="post">
            <input type="hidden" name="ref_eve" value="<?= htmlspecialchars($evenement->id_evenement) ?>">
            <input type="hidden" name="refUser" value="<?= htmlspecialchars($_SESSION['utilisateur']['id_user']) ?>">

            <div class="mb-3">
                <label for="type_eve">Type d’évènement</label>
                <input type="text" readonly class="form-control" id="type_eve"
                       value="<?= htmlspecialchars($evenement->type_eve) ?>">
            </div>
            <div class="mb-3">
                <label for="status">Status de l'evenement</label>
                <input type="text" readonly class="form-control" id="status"
                       value="<?= htmlspecialchars($evenement->status) ?>">
            </div>
            <div class="mb-3">
                <label for="lieu_eve">Lieu</label>
                <input type="text" readonly class="form-control" id="lieu_eve"
                       value="<?= htmlspecialchars($evenement->lieu_eve) ?>">
            </div>

            <div class="mb-3">
                <label for="desc_eve">Description</label>
                <textarea readonly class="form-control" id="desc_eve"><?= htmlspecialchars($evenement->desc_eve) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="element_eve">Éléments nécessaires</label>
                <input type="text" readonly class="form-control" id="element_eve"
                       value="<?= htmlspecialchars($evenement->element_eve) ?>">
            </div>

            <div class="mb-3">
                <label for="nb_place">Places disponibles</label>
                <?php if($evenement->nb_place-$nbInscrits===0):?>
                    <input type="text" readonly class="form-control" id="nb_place"
                           value="Evenement complet">
                <?php else: ?>
                <input type="number" readonly class="form-control" id="nb_place"
                       value="<?= htmlspecialchars($evenement->nb_place-$nbInscrits) ?>">
                <?php endif; ?>
            </div>

            <div class="text-center mt-4">


                <?php if (!in_array($_SESSION['utilisateur']['id_user'],$superviseurs,true)) : ?>
                    <?php if ($estInscrit): ?>
                        <button class="btn btn-primary" <?php if ($evenement->nb_place-$nbInscrits==0) {
                         echo'id="liveAlertBtn"';
                        }else {
                            echo'type="submit"';
                        }?>>
                            <i class="bi bi-person-plus"></i> Participer
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal">
                            <i class="bi bi-trash"></i> Se desinscrire
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($_SESSION["utilisateur"]["role"]=="Professeur" && $evenement->est_valide==0 ): // ajouter le fait que l'evenement doit etre crée par un etudient pour afficher le btn?>

                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#confirmEvenementModal">
                        <i class="bi bi-person-check"></i> Valider l'evenement
                    </button>
                <?php endif; ?>
            </div>
        </form>
        <?php if (in_array($_SESSION['utilisateur']['id_user'],$superviseurs,true)): ?>
            <div class="text-center mt-3">
                <a href="evenementUpdate.php?id=<?= $evenement->id_evenement?>" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i> Modifier l’évènement
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- MODALE DE CONFIRMATION -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmModalLabel">Confirmer la desinscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir vous desinscrire ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="post" action="../../src/treatment/traitementDesinscriptionEvenement.php">
                    <input type="hidden" name="idevenement" value="<?= htmlspecialchars($evenement->id_evenement) ?>">
                    <input type="hidden" name="refuser" value="<?= htmlspecialchars($eveUser->getRefUser()) ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-exclamation-octagon"></i> Se Desinscrire
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- MODALE DE CONFIRMATION POUR VALIDER LES EVENEMENTS -->
<div class="modal fade" id="confirmEvenementModal" tabindex="-1" aria-labelledby="confirmEvenementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="confirmEvenementModalLabel">Confirmer la validation de l'evenement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir valider l'evenement ? Vous allez devenir superviseur de cette evenement et vous ne pourrez plus le quitter.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="post" action="../../src/treatment/traitementValidationEvenement.php">
                    <input type="hidden" name="idEvenement" value="<?= htmlspecialchars($evenement->id_evenement) ?>">
                    <input type="hidden" name="refUser" value="<?= htmlspecialchars($eveUser->getRefUser()) ?>">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-person-fill-check"></i> Valider
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

    const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
    const appendAlert = (message, type) => {
        const wrapper = document.createElement('div')
        wrapper.innerHTML = [
            `<div class="alert alert-${type} alert-dismissible" role="alert">`,
            `   <div>${message}</div>`,
            '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
            '</div>'
        ].join('')

        alertPlaceholder.append(wrapper)
    }

    const alertTrigger = document.getElementById('liveAlertBtn')
    if (alertTrigger) {
        alertTrigger.addEventListener('click', () => {
            appendAlert("l'evenement est complet, soudoyez quelqu'un si vous voulez vous inscrire", 'danger')
        })
    }

</script>
</body>
</html>