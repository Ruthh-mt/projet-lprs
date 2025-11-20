<?php
require_once "../bdd/config.php";
require_once "../modele/ModeleEvenementUser.php";
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
$refUser = $_POST["refuser"];

if ($idEve === '' || $refUser === '') {
    redirectWith('error', "Veuillez choisir un evenement.", '../../view/evenements.php');
}
try {
    $evenementUserRepository = new EvenementUserRepository();
    $evenementuser = new ModeleEvenementUser(array(
        "refEvenement" => $idEve,
        "refUser" => $refUser,
    ));
    $evenementUserRepository->desinscription($evenementuser);
    redirectWith('success', "Vous avez bien été desinscrit de l'evenement ", '../../view/crudEvenement/evenementRead.php?id=' . $idEve);
    session_write_close();
} catch (PDOException $e) {
    redirectWith('error', "Erreur de la desinscription  : " . $e->getMessage(), '../../view/crudEvenement/evenementRead.php?id=' . $idEve);
}

