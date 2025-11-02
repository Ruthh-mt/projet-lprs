<?php
require_once "../modele/ModelePost.php";
require_once "../bdd/config.php";
require_once "../repository/PostRepository.php";
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
   $_SESSION['error'] = "Méthode invalide.";
    header('Location: ../../view/crudPost/postCreate.php');
}
$refUser = $_POST["ref_user"];
$titrePost = $_POST["titre_post"];
$contenuPost = $_POST["contenu_post"];
$dateHeurePost = date("Y-m-d H:i:s");
//$canal$_POST["id_user"];


if($titrePost==='' || $contenuPost==='' || $refUser===''){
    $_SESSION['error'] = "Veuillez remplir tous les champs";
    header('Location: ../../view/crudPost/postCreate.php');
}
try {
    $post = new ModelePost(array(
       "refUser" => $refUser,
       "titrePost" => $titrePost,
       "contenuPost" => $contenuPost,
       "dateHeurePost" => $dateHeurePost
    ));
    $postRepository = new PostRepository();
    $postRepository->createPost($post);
  $_SESSION['success']= "Le Post a bien été crée";
    header('Location:../../view/forum.php');
    session_write_close();
} catch (PDOException $e) {
    $_SESSION['error']="Erreur lors de la creation du post : ". $e->getMessage();
    header('Location:../../view/crudPost/PostCreate.php');
}

