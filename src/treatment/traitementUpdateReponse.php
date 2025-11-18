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
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWith("error","Methode invalide",'../../view/forum.php');
}
$refUser = $_POST["refUser"];
$idResponse=$_POST["idResponse"];
$contenuReponse = $_POST["contenu_reponse"];
$dateHeureReponse = date("Y-m-d H:i:s");
$refPost=$_POST["refPost"];


if($refPost==="" || $contenuReponse==='' || $refUser==='' ||$idResponse==='' ){
    redirectWith("error","Veuillez remplir tous les champs",'../../view/crudPost/postRead?id='.$refPost.'.php');
}
try {
    $reponse = new ModeleReponse(array(
        "refUser" => $refUser,
        "contenuReponse" => $contenuReponse,
        "dateHeureReponse" => $dateHeureReponse,
        "idReponse"=>$idResponse
    ));

    $ReponseRepository = new ReponseRepository();
    $ReponseRepository->updateReponse($reponse);
    redirectWith("sucess","Le commentaire a bien été modifié",'../../view/crudPost/postRead?id='.$refPost.'.php');
    session_write_close();
} catch (PDOException $e) {
    redirectWith("error","Erreur lors de la modification du Commentaire : ". $e->getMessage(),'../../view/crudPost/postRead?id='.$refPost.'.php');
}


