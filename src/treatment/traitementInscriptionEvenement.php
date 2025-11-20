<?php

require_once "../modele/ModeleEvenement.php";
require_once "../bdd/config.php";
require_once "../repository/EvenementRepository.php";
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

$refUser = $_POST["refUser"];
$refEvenement = $_POST["ref_eve"];

if ($refUser === '' || $refEvenement === '') {
    redirectWith('error', "Vous n'avez pas l'air connecté ", '../../view/crudEvenement/evenementRead?id=' . $refEvenement . '.php');
}
try {
    $evenementUser = new ModeleEvenementUser(array(
        "refUser" => $refUser,
        "refEvenement" => $refEvenement,
        "estSuperviseur" => 0
    ));

    $evenementUserRepository = new EvenementUserRepository();
    $estDejaInscrit = $evenementUserRepository->verifDejaInscritEvenement($evenementUser);
    if ($estDejaInscrit) {
        $evenementUserRepository->inscriptionEvenementUser($evenementUser);
        redirectWith('success', "Vous avez bien été inscrit", '../../view/crudEvenement/evenementRead?id=' . $refEvenement . '.php');
    } else {
        redirectWith('Error', "Vous etes deja inscrit ", '../../view/crudEvenement/evenementRead?id=' . $refEvenement . '.php');
    }
    session_write_close();
} catch (PDOException $e) {
    redirectWith('error', "Erreur de l'inscription à l'evenement : " . $e->getMessage(), '../../view/evenements.php');
}

