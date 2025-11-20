<?php
require_once "../bdd/config.php";
require_once "../modele/ModeleReponse.php";
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

$refPost = $_POST["idPost"];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWith('error', "Méthode invalide.", '../../view/crudPost/postRead.php?id=' . $refPost);
}
$idReponse = $_POST["idReponse"];

if ($idReponse === '' || $refPost === '') {
    redirectWith('error', "Veuillez choisir une reponse.", '../../view/crudPost/postRead.php?id=' . $refPost);
}
try {
    $reponseRepo = new ReponseRepository();
    $reponse = new ModeleReponse(array(
        "refPost" => $refPost,
        "idReponse" => $idReponse,
    ));
    $reponseRepo->deleteReponse($reponse);
    redirectWith('success', "Reponse supprimé ", '../../view/crudPost/postRead.php?id=' . $refPost);
    session_write_close();
} catch (PDOException $e) {
    redirectWith('error', "Erreur de la suppression de la reponse : " . $e->getMessage(), '../../view/crudPost/postRead.php?id=' . $refPost);
}

