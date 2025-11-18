<?php
require_once "../../src/modele/ModelePost.php";
require_once "../../src/modele/ModeleReponse.php";
require_once "../../src/repository/PostRepository.php";
require_once "../../src/repository/ReponseRepository.php";
require_once "../../src/bdd/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('Aucun événement sélectionné'); window.location.href='../forum.php';</script>";
    exit;
}

$postRepo = new PostRepository();
$post = $postRepo->getPostById(new ModelePost(["idPost" => $id]));
$reponseRepo = new ReponseRepository();
$reponses=$reponseRepo->getAllReponsebyPostId($id)
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détail d’un post • LPRS</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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
        <a href="../forum.php" class="btn btn-outline-light">Retour aux posts</a>
    </div>
</header>
<!-- SECTION DETAIL -->
<div class="container mb-5">
    <div class="section-offre">
        <div class="offre-header d-flex justify-content-between align-items-center">
            <?php $username=$postRepo->findUsername($post->getIdPost());
            echo'<h5 class="fw-bold"> '.htmlspecialchars($username["prenom"]." ".$username["nom"]).'</h5>
            <h2 class="fw-bold">'. htmlspecialchars($post->getTitrePost()).'</h2>';?>
            <button type="button" class="btn btn-outline-light" onclick="window.location.href='../forum.php'">
                <i class="bi bi-arrow-left-circle"></i> Retour
            </button>
        </div>
            <div class="mb-3">
                <p readonly class="form-control" ><?= htmlspecialchars($post->getContenuPost()) ?></p>
            </div>
            <?php
            $createur = $post->getRefUser();
            if(!empty($_SESSION["utilisateur"])&& $createur==$_SESSION['utilisateur']['id_user']){
                echo'<div class="text-center mt-3">
                <a href="postUpdate.php?id='. $post->getIdPost().'" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i> Modifier le post
                </a>
            </div>';
            }
            ?>
            <button class="btn btn-primary" type="button" <?php if(empty($_SESSION["utilisateur"])) :?>
                data-bs-toggle="modal" data-bs-target="#connectezVousModal"
            <?php else:?>
                data-bs-toggle="collapse" data-bs-target="#showCommentCreateForm" aria-expanded="false" aria-controls="showCommentCreateForm"
             <?php endif;?>
            >Commenter</button>
            <div class="collapse collapse-horizontal" id="showCommentCreateForm">
                <form method="post" action="../../src/treatment/traitementAjoutReponse">
                    <input type="hidden" name="refPost" value="<?= htmlspecialchars($post->getidPost()); ?>">
                    <input type="hidden" name="refUser" value="<?= htmlspecialchars($_SESSION['utilisateur']['id_user']) ?>">
                    <label for="contenu" > Entrer un commentaire</label>
                    <textarea class="form-control" id="contenu" name="contenu_reponse" ></textarea>
                    <input type="submit" class="btn btn-primary" name="submit" value="Publier" />
                </form>
            </div>
        <br>
        <h5>Commentaire</h5>
        <?php
        foreach ($reponses as $reponse) :
            $userpost = $reponseRepo->findUsernameReponse(New ModeleReponse(["idReponse"=>$reponse->id_reponse,
                "refPost"=>$reponse->ref_post]));
        $count=true;?>
        <div class="chat chat-start"> <!--- boucle et condition pour metre les chat des deux coter a faire-->
            <div class="chat-header">
                <?=$userpost["prenom"]." ".$userpost["nom"]?>
                <?php if($reponse->ref_user==$_SESSION['utilisateur']['id_user'])  :?>
                    <div class="dropdown">
                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" data-bs-toggle="collapse" data-bs-target="#showCommentUpdateForm" aria-expanded="false" aria-controls="showCommentUpdateForm" ><i class="bi bi-pencil-square"></i></button></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-trash"></i></a></li>
                        </ul>
                    </div>
                <?php endif;?>

                <time class="text-xs opacity-50"></time>
            </div>
            <div class="chat-bubble"><?=$reponse->contenu_reponse?></div>
            <div class="chat-footer opacity-50"><?=$reponse->date_heure_reponse?>
                <br>
            <?php if($reponse->ref_user==$_SESSION['utilisateur']['id_user'])  :?>
                <button data-bs-toggle="collapse" data-bs-target="#showCommentUpdateForm" aria-expanded="false" aria-controls="showCommentUpdateForm" ><i class="bi bi-pencil-square"></i></button>
            <?php endif;?>
            </div>
            <div class="collapse collapse-horizontal" id="showCommentUpdateForm">
                <form method="post" action="../../src/treatment/traitementUpdateReponse.php">
                    <input type="hidden" name="refPost" value="<?= htmlspecialchars($post->getidPost()); ?>">
                    <input type="hidden" name="idReponse" value="<?= htmlspecialchars($reponse->id_reponse); ?>">
                    <input type="hidden" name="refUser" value="<?= htmlspecialchars($_SESSION['utilisateur']['id_user']) ?>">
                    <label for="contenu" > Entrer un commentaire</label>
                    <textarea class="form-control" id="contenu" name="contenu_reponse" ></textarea>
                    <input type="submit" class="btn btn-primary" name="submit" value="Modifier" />
                </form>
            </div>
            <?php endforeach;?>
        </div>
        </div>
    </div>
<div class="modal fade" id="connectezVousModal" tabindex="-1" aria-labelledby="connectezVousModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="connectezVousModalLabel">PTDR t'es qui ? </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Veuillez vous connectez
            </div>
            <div class="modal-footer">
                <button href="../inscription.php" type="button" class="btn btn-primary" data-bs-dismiss="modal">Se connecter</button>
                <button href="../inscription.php" type="button" class="btn btn-secondary" data-bs-dismiss="modal">S'inscrire</button>
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
</body>
</html>
