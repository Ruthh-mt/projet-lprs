<?php

require_once "../modele/Evenement.php";
require_once "../bdd/config.php";
require_once "../repository/EvenementRepository.php";
require_once "../modele/EvenementUser.php";
require_once "../repository/EvenementUserRepository.php";
session_start();

function redirectWith(string $type, string $message, string $target): void
{
    $_SESSION[$type] = $message;
    session_write_close();
    header("Location: $target");
    exit();
}

$refUser = $_POST["refUser"];
$refEvenement = $_POST["ref_eve"];

if ($refUser===''||$refEvenement==='') {
    redirectWith('error', "Vous n'avez pas l'air connecté ", '../../view/crudEvenement/evenementRead.php');
}
try {
    $evenementUser = new EvenementUser(array(
        "refUser" => $refUser,
        "refEvenement" => $refEvenement,
        "estSuperviseur" => null
    ));

    $evenementUserRepository= new EvenementUserRepository();
    $estDejaInscrit=$evenementUserRepository->verifDejaInscritEvenement($evenementUser);
    if($estDejaInscrit){
        $evenementUserRepository->inscriptionEvenementUser($evenementUser);
        redirectWith('success', "Vous avez bien été inscrit", '../../view/crudEvenement/afficherEvenement?id='.$refEvenement.'.php');
    }
    else{
        redirectWith('Error', "Vous etes deja inscrit ", '../../view/crudEvenement/afficherEvenement?id='.$refEvenement.'.php');
    }
    session_write_close();
} catch (PDOException $e) {
    redirectWith('error', "Erreur de l'inscription à l'evenement : " . $e->getMessage(), '../../view/evenements.php');
}

