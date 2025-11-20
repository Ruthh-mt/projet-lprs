<?php
require_once "../../src/modele/ModelePost.php";
require_once "../../src/repository/PostRepository.php";
require_once "../../src/bdd/config.php";

session_start();
$prefix = explode('/view/', $_SERVER['HTTP_REFERER'])[0] . '/public';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('Aucun post sélectionné'); window.location.href='../forum.php';</script>";
    exit;
}

$postRepo = new PostRepository();
$post = $postRepo->getPostById(new ModelePost(["idPost" => $id]));
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier un post • LPRS</title>

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
        <a href="../forum.php" class="btn btn-outline-light">
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
            <h2 class="fw-bold">Modifier un post</h2>
            <button type="button" class="btn btn-outline-light"
                    onclick="window.location.href='postRead.php?id=<?= $post->getIdPost() ?>'">
                <i class="bi bi-arrow-left-circle"></i> Retour
            </button>
        </div>

        <form class="mt-4" action="../../src/treatment/traitementUpdatePost.php" method="post">

            <input type="hidden" name="idPost" value="<?= htmlspecialchars($post->getIdPost()) ?>">
            <input type="hidden" name="refUser" value="<?= htmlspecialchars($_SESSION['utilisateur']['id_user']) ?>">

            <div class="mb-3">
                <label for="titre_eve">Titre du post</label>
                <input class="form-control" type="text" id="titre_eve" name="titrePost"
                       value="<?= htmlspecialchars($post->getTitrePost()) ?>" required>
            </div>

            <div class="mb-3">
                <label for="canal">Canal</label>
                <select class="form-select" aria-label="Default select example" name="canal" required>

                    <?php //if($_SESSION["utilisateur"]['role']!="Étudiant"|| $evenement->est_valide===1 ):?>
                    <?php
                    $canaux[]="general";
                    if ($_SESSION["utilisateur"]['role'] === "Étudiant" || $_SESSION["utilisateur"]['role'] === "Professeur") {
                        $canaux[] = "profediant";
                    }
                    elseif ($_SESSION["utilisateur"]['role'] === "Alumni" || $_SESSION["utilisateur"]['role'] === "Partenaire"){
                        $canaux[] = "entrumnis";
                    }
                    elseif ($_SESSION["utilisateur"]['role'] === 'Gestionnaire'){
                        $canaux[] = "admin";
                    }

                    for ($i = 0; $i < count($canaux); $i++) :
                        if ($post->getCanal() == $canaux[$i]) :?>
                            echo '<option selected value="<?=$canaux[$i]?>"><?php if($canaux[$i]== "general"){echo'Canal General';}
                        elseif($canaux[$i]=="profediant"){echo'Canal Etudiant/Professeur';}
                        elseif($canaux[$i]=="entrumnis"){echo'Canal Alumni/Entreprise';}
                        elseif($canaux[$i]=="admin"){echo'Canal Gestionnaire';} ?></option>';
                         <?php else :?>
                            <option value="<?= $canaux[$i]?>"><?php if($canaux[$i]== "general"){echo'Canal General';}
                                elseif($canaux[$i]=="profediant"){echo'Canal Etudiant/Professeur';}
                                elseif($canaux[$i]=="entrumnis"){echo'Canal Alumni/Entreprise';}
                                elseif($canaux[$i]=="admin"){echo'Canal Gestionnaire';} ?></option>';

                    <?php endif;
                    endfor;
                    ?>
                    </select>
            </div>

            <div class="mb-3">
                <label for="contenu">Description</label>
                <textarea class="form-control" id="contenu"  name="contenuPost"
                          rows="5"><?= htmlspecialchars($post->getContenuPost()) ?></textarea>
            </div>


            <div class="d-flex justify-content-between mt-4">
                <div>
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-save"></i> Enregistrer les modifications
                    </button>
                    <a href="postRead.php?id=<?=$post->getIdPost()?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Annuler
                    </a>
                </div>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal">
                    <i class="bi bi-trash"></i> Supprimer
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
                Êtes-vous sûr de vouloir supprimer ce post ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="post" action="../../src/treatment/traitementDeletePost.php">
                    <input type="hidden" name="idPost" value="<?= htmlspecialchars($post->getIdPost()) ?>">
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
    const ta = document.getElementById('contenu');

    // Adjust height on input
    ta.addEventListener('input', () => autoResize(ta));

    // Adjust height on page load for prefilled content
    window.addEventListener('load', () => autoResize(ta));
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
