<?php
require_once "../bdd/config.php";
require_once "../modele/ModeleEvenementUser.php";
require_once "../modele/ModeleEvenement.php";
require_once "../repository/EvenementUserRepository.php";
require_once "../repository/EvenementRepository.php";
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

$refUser = $_POST["refUser"];
$refEvenement = $_POST["idEvenement"];

if ($refUser===''||$refEvenement==='') {
    redirectWith('error', "Vous n'avez pas l'air connecté ", '../../view/evenements.php');
}
try {
    $evenementUser = new ModeleEvenementUser(array(
        "refUser" => $refUser,
        "refEvenement" => $refEvenement,
        "estSuperviseur" => 1
    ));
    $evenementrepo=new EvenementRepository();
    $evenement=new ModeleEvenement([
        "idEvenement"=>$refEvenement,
        "status"=>"actif",
        "estValide"=>1]);
    $evenementrepo->validateEvenement($evenement);
    $evenementUserRepository= new EvenementUserRepository();
    $evenementUserRepository->addSuperviseur($evenementUser);
    redirectWith('success', "L'evenement a bien été validé, N'oubliez c'est pour la vie", '../../view/crudEvenement/evenementRead?id='.$refEvenement.'.php');

    session_write_close();
} catch (PDOException $e) {
    var_dump($e->getMessage());
    redirectWith('error', "Erreur de la validation de l'evenement : " . $e->getMessage(), '../../view/evenements.php');
}

