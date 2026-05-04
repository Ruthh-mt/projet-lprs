<?php
require_once "../../src/modele/ModeleEvenement.php";
require_once "../../src/modele/ModeleEvenementUser.php";
require_once "../../src/modele/ModeleAvis.php";
require_once "../../src/repository/EvenementRepository.php";
require_once "../../src/repository/EvenementUserRepository.php";
require_once "../../src/repository/AvisRepository.php";
require_once "../../src/bdd/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['utilisateur'])) {
    header("Location: ../connexion.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('Aucun evenement selectionne'); window.location.href='../evenements.php';</script>";
    exit;
}

$evenementRepo = new EvenementRepository();
$avisRepo = new AvisRepository();

$evenement = $evenementRepo->getAnEvenement(new ModeleEvenement(["idEvenement" => $id]));

if (!$evenement || $evenement->status !== 'terminé') {
    echo "<script>alert('Cet evenement n\\'est pas termine'); window.location.href='evenementRead.php?id=" . htmlspecialchars($id) . "';</script>";
    exit;
}

$userId = $_SESSION['utilisateur']['id_user'];
$monAvis = $avisRepo->getAvisByUserAndEvenement($userId, $id);

if (!$monAvis) {
    header("Location: evenementAvisCreate.php?id=" . htmlspecialchars($id));
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier mon avis • LPRS</title>
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
        body { background-color: #f8f9fa; }
        .section-offre { background: white; border-radius: 1rem; box-shadow: 0 0 15px rgba(0,0,0,0.1); padding: 2rem; }
        .offre-header { background-color: #212529; color: white; border-radius: .75rem .75rem 0 0; padding: 1.5rem; border-bottom: 2px solid #0d6efd; }
        .offre-header h2 { margin: 0; }
        label { font-weight: 600; margin-bottom: 6px; }
        .star-rating i { transition: color 0.15s, transform 0.15s; }
        .star-rating i:hover { transform: scale(1.2); }
    </style>
</head>
<body>

<header class="d-flex flex-wrap align-items-center justify-content-between py-3 mb-4 border-bottom bg-dark px-4">
    <div class="d-flex align-items-center">
        <a href="../accueil.php" class="d-inline-flex text-decoration-none align-items-center">
            <img src="https://media.tenor.com/1DV7nkfj5OkAAAAM/blobgodeto-blobdance.gif"
                 class="rounded-circle mx-3" style="max-width: 40px; height: auto;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <div>
        <a href="evenementRead.php?id=<?= htmlspecialchars($evenement->id_evenement) ?>" class="btn btn-outline-light">
            <i class="bi bi-arrow-left-circle"></i> Retour a l'evenement
        </a>
    </div>
</header>

<?php if (!empty($_SESSION["toastr"])) {
    $type = $_SESSION["toastr"]["type"];
    $message = $_SESSION["toastr"]["message"];
    echo '<script>
        toastr.options = { "closeButton": true, "positionClass": "toast-bottom-full-width", "preventDuplicates": true, "timeOut": "5000", "showMethod": "slideDown", "hideMethod": "slideUp" }
        toastr.' . $type . '("' . $message . '");
    </script>';
    unset($_SESSION['toastr']);
} ?>

<div class="container mb-5">
    <div class="section-offre">
        <div class="offre-header">
            <h2 class="fw-bold"><i class="bi bi-pencil-square"></i> Modifier mon avis</h2>
            <p class="mb-0 mt-2">Evenement : <?= htmlspecialchars($evenement->titre_eve) ?></p>
        </div>

        <form class="mt-4" method="post" action="../../src/treatment/traitementUpdateAvis.php">
            <input type="hidden" name="idAvis" value="<?= htmlspecialchars($monAvis->id_avis) ?>">
            <input type="hidden" name="refEvenement" value="<?= htmlspecialchars($evenement->id_evenement) ?>">

            <div class="mb-4">
                <label class="form-label">Votre note <span class="text-danger">*</span></label>
                <div class="star-rating" id="starRating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="bi bi-star<?= $i <= $monAvis->note ? '-fill' : '' ?> fs-2" data-value="<?= $i ?>"
                           style="cursor: pointer; color: <?= $i <= $monAvis->note ? '#ffc107' : '#ccc' ?>;"></i>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="note" id="noteInput" value="<?= htmlspecialchars($monAvis->note) ?>" required>
                <div class="form-text">Cliquez sur les etoiles pour noter de 1 a 5.</div>
            </div>

            <div class="mb-4">
                <label for="commentaire" class="form-label">Commentaire <span class="text-muted">(facultatif)</span></label>
                <textarea class="form-control" name="commentaire" id="commentaire" rows="4"
                          placeholder="Partagez votre experience sur cet evenement..." maxlength="2048"><?= htmlspecialchars($monAvis->commentaire ?? '') ?></textarea>
                <div class="form-text"><span id="charCount"><?= mb_strlen($monAvis->commentaire ?? '') ?></span>/2048 caracteres.</div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Modifier mon avis
                </button>
                <a href="evenementRead.php?id=<?= htmlspecialchars($evenement->id_evenement) ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>
            </div>
        </form>

        <hr>
        <div class="text-end">
            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteAvisModal">
                <i class="bi bi-trash"></i> Supprimer mon avis
            </button>
        </div>
    </div>
</div>

<!-- MODALE SUPPRESSION AVIS -->
<div class="modal fade" id="confirmDeleteAvisModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Supprimer mon avis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Etes-vous sur de vouloir supprimer votre avis ? Cette action est irreversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="post" action="../../src/treatment/traitementDeleteAvis.php">
                    <input type="hidden" name="idAvis" value="<?= htmlspecialchars($monAvis->id_avis) ?>">
                    <input type="hidden" name="refEvenement" value="<?= htmlspecialchars($evenement->id_evenement) ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('#starRating .bi');
        const noteInput = document.getElementById('noteInput');
        const commentaire = document.getElementById('commentaire');
        const charCount = document.getElementById('charCount');

        commentaire.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        stars.forEach(function(star) {
            star.addEventListener('click', function() {
                const val = this.getAttribute('data-value');
                noteInput.value = val;
                updateStars(val);
            });
            star.addEventListener('mouseenter', function() {
                const val = this.getAttribute('data-value');
                stars.forEach(function(s) {
                    s.style.color = s.getAttribute('data-value') <= val ? '#ffc107' : '#ccc';
                });
            });
        });

        document.getElementById('starRating').addEventListener('mouseleave', function() {
            updateStars(noteInput.value);
        });

        function updateStars(val) {
            stars.forEach(function(s) {
                const sv = s.getAttribute('data-value');
                if (val && sv <= val) {
                    s.classList.remove('bi-star');
                    s.classList.add('bi-star-fill');
                    s.style.color = '#ffc107';
                } else {
                    s.classList.remove('bi-star-fill');
                    s.classList.add('bi-star');
                    s.style.color = '#ccc';
                }
            });
        }
    });
</script>
</body>
</html>
