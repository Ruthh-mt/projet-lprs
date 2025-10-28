<?php
require_once "../modele/Reponse.php";
require_once "../bdd/config.php";
require_once "../repository/ReponseRepository.php";
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Méthode invalide.";
    header('Location: ../../view/crudPost/postCreate.php');
}
$refUser = $_POST["refUser"];
$contenuReponse = $_POST["contenu_reponse"];
$dateHeureReponse = date("Y-m-d H:i:s");
$refPost=$_POST["refPost"];


if($refPost==="" || $contenuReponse==='' || $refUser===''){
    $_SESSION['error'] = "Veuillez remplir tous les champs";
    header('Location: ../../view/crudPost/afficherPost'.$refPost.'.php');
}
try {
    $reponse = new Reponse(array(
        "refUser" => $refUser,
        "contenuReponse" => $contenuReponse,
        "dateHeureReponse" => $dateHeureReponse,
        "refPost" => $refPost,
    ));

    $ReponseRepository = new ReponseRepository();
    $ReponseRepository->createReponse($reponse);
    $_SESSION['success']= "Le commentaire a bien été publier";
    header('Location:../../view/crudPost/afficherPost?id='.$refPost.'.php');
    session_write_close();
} catch (PDOException $e) {
    $_SESSION['error']="Erreur lors de la creation du Commentaire : ". $e->getMessage();
    header('Location:../../view/crudPost/PostCreate.php');
}


