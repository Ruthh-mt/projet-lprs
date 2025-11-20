<?php

require_once "../modele/ModelePost.php";
require_once "../modele/ModeleReponse.php";
require_once "../bdd/config.php";
require_once "../repository/PostRepository.php";
require_once "../repository/ReponseRepository.php";


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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWith('error', "Méthode invalide.", '../../view/forum.php');
}
$idPost = $_POST["idPost"];

if ($idPost === '') {
    redirectWith('error', "Veuillez choisir un post.", '../../view/forum.php');
}
try {
    $post = new ModelePost(array(
        "idPost" => $idPost
    ));
    $postRepo = new PostRepository();
    $reponseRepo = new ReponseRepository();
    $reponse = new ModeleReponse(array(
        "refPost" => $post->getIdPost(),
    ));
    $postRepo->deletePost($post);
    redirectWith('success', "Le post a bien été Supprimé", '../../view/forum.php');
    session_write_close();
} catch (PDOException $e) {
    redirectWith('error', "Erreur de la suppression du post : " . $e->getMessage(), '../../view/crudPost/postUpdate.php?id=' . $idPost);
}


