<?php
require_once "../modele/ModelePost.php";
require_once "../bdd/config.php";
require_once "../repository/PostRepository.php";
session_start();
function redirectWith(string $type, string $message, string $target): void {
    $_SESSION['toastr']=[
        "type"=>$type,
        "message"=>$message,
    ];
    session_write_close();
    header("Location: $target",$_SESSION["toastr"]["type"]);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
   $_SESSION['error'] = "Méthode invalide.";
redirectWith("error","Methode invalide","../../view/forum.php");
}
$refUser = $_POST["ref_user"];
$titrePost = $_POST["titre_post"];
$contenuPost = $_POST["contenu_post"];
$canal=$_POST["canal"];
date_default_timezone_set('Europe/Paris');
$dateHeurePost = date("Y-m-d H:i:s");


if($titrePost==='' || $contenuPost==='' || $refUser==='' || $canal===''){
    redirectWith("error","Veuillez remplir tous les champs",'../../view/crudPost/postCreate.php');
}
try {
    $post = new ModelePost(array(
       "refUser" => $refUser,
       "titrePost" => $titrePost,
       "canal" => $canal,
       "contenuPost" => $contenuPost,
       "dateHeurePost" => $dateHeurePost
    ));
    $postRepository = new PostRepository();
    $postRepository->createPost($post);
    redirectWith("success","Le post a bien été crée",'../../view/forum.php');
    session_write_close();
} catch (PDOException $e) {
    redirectWith("error","Erreur lors de la creation du post".$e->getMessage(),'../../view/crudPost/PostCreate.php');
}

