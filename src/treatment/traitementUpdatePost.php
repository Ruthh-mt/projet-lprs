<?php
require_once "../modele/ModelePost.php";
require_once "../bdd/config.php";
require_once "../repository/PostRepository.php";

session_start();

function redirectWith(string $type, string $message, string $target): void
{
    $_SESSION['toastr'] = [
        "type" => $type,
        "message" => $message,
    ];
    session_write_close();
    header("Location: $target", $_SESSION["toastr"]["type"]);
    exit();
}

$idPost = $_POST['idPost'];


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWith('error', "Méthode invalide.", '../../view/crudPost/evenementUpdate.php');
}
$titrePost = $_POST['titrePost'];
$contenuPost = $_POST['contenuPost'];
$canal = $_POST['canal'];


if ($idPost === '' || $titrePost === '' || $contenuPost === '' || $canal === '') {
    redirectWith('error', "Veuillez remplir tout les champs.", '../../view/crudPost/postUpdate.php?id=' . $idPost);
}
try {
    $post = new ModelePost(array(
        "idPost" => $idPost,
        "titrePost" => $titrePost,
        "contenuPost" => $contenuPost,
        "canal" => $canal
    ));
    $postRepo = new PostRepository();
    $postRepo->updatePost($post);
    redirectWith('success', "Le post a bien été modifié", '../../view/crudPost/postRead.php?id=' . $post->getIdPost());
    session_write_close();
} catch (PDOException $e) {
    redirectWith('error', "Erreur de la modification du Post : " . $e->getMessage(), '../../view/crudPost/postUpdate.php?id=' . $idPost);
}

