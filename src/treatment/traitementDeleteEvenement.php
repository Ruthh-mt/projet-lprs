<?php

require_once "../modele/ModeleEvenement.php";
require_once "../modele/ModeleEvenementUser.php";
require_once "../bdd/config.php";
require_once "../repository/EvenementRepository.php";
require_once "../repository/EvenementUserRepository.php";


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
    redirectWith('error', "Méthode invalide.", '../../view/evenements.php');
}
$idEve = $_POST["idevenement"];

if ($idEve === '') {
    redirectWith('error', "Veuillez choisir un evenement.", '../../view/evenements.php');
}
try {
    $evenement = new ModeleEvenement(array(
        "idEvenement" => $idEve,

    ));
    $evenementRepository = new EvenementRepository();
    $evenementUserRepository = new EvenementUserRepository();
    $evenementuser = new ModeleEvenementUser(array(
        "refEvenement" => $idEve,
    ));
    $evenementUserRepository->deleteUserEvenement($evenementuser);
    $evenementRepository->deleteEvenement($evenement);
    redirectWith('success', "L'evenement a bien été Supprimé", '../../view/evenements.php');
    session_write_close();
} catch (PDOException $e) {
    redirectWith('error', "Erreur de la suppression de l'evenement : " . $e->getMessage(), '../../view/crudEvenement/evenementUpdate.php?id=' . $idEve);
}

