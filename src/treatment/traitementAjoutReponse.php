<?php
require_once "../modele/ModeleReponse.php";
require_once "../bdd/config.php";
require_once "../repository/ReponseRepository.php";
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
$refPost=$_POST["refPost"];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWith("error","Methode invalide", '../../view/crudPost/postRead?id='.$refPost.'.php');
}
$refUser = $_POST["refUser"];
$contenuReponse = $_POST["contenu_reponse"];
date_default_timezone_set('Europe/Paris');
$dateHeureReponse = date("Y-m-d H:i:s");


if($refPost==="" || $contenuReponse==='' || $refUser===''){
    redirectWith("error","Veuillez remplir tous les champs",'../../view/crudPost/postRead?id='.$refPost.'.php');
}
try {
    $reponse = new ModeleReponse(array(
        "refUser" => $refUser,
        "contenuReponse" => $contenuReponse,
        "dateHeureReponse" => $dateHeureReponse,
        "refPost" => $refPost,
    ));

    $ReponseRepository = new ReponseRepository();
    $ReponseRepository->createReponse($reponse);
    redirectWith("sucess","Le commentaire a bien été publié",'../../view/crudPost/postRead?id='.$refPost.'.php');
    session_write_close();
} catch (PDOException $e) {
    redirectWith("error","Erreur lors de la creation du Commentaire : ". $e->getMessage(),'../../view/crudPost/postRead?id='.$refPost.'.php');
}


