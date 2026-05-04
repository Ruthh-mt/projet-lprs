<?php
require_once "../bdd/config.php";
require_once "../modele/ModeleEvenement.php";
require_once "../repository/EvenementRepository.php";
require_once "../repository/EvenementUserRepository.php";
require_once "../modele/ModeleEvenementUser.php";
session_start();

function redirectWith(string $type, string $message, string $target): void
{
    $_SESSION['toastr'] = [
        "type" => $type,
        "message" => $message,
    ];
    session_write_close();
    header("Location: $target");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWith('error', "Methode non autorisee", '../../view/evenements.php');
}

if (!isset($_SESSION['utilisateur'])) {
    redirectWith('error', "Vous devez etre connecte", '../../view/evenements.php');
}

$idEvenement = $_POST["idEvenement"] ?? '';

if ($idEvenement === '') {
    redirectWith('error', "Evenement introuvable", '../../view/evenements.php');
}

try {
    $evenementUserRepository = new EvenementUserRepository();
    $superviseurs = $evenementUserRepository->getSuperviseur($idEvenement);

    if (!in_array($_SESSION['utilisateur']['id_user'], $superviseurs, true)) {
        redirectWith('error', "Vous n'etes pas autorise a terminer cet evenement", '../../view/crudEvenement/evenementRead.php?id=' . $idEvenement);
    }

    $evenement = new ModeleEvenement([
        "idEvenement" => $idEvenement
    ]);

    $evenementRepo = new EvenementRepository();
    $evenementRepo->terminerEvenement($evenement);

    redirectWith('success', "L'evenement a ete marque comme termine. Les participants peuvent maintenant laisser des avis.", '../../view/crudEvenement/evenementRead.php?id=' . $idEvenement);

} catch (PDOException $e) {
    redirectWith('error', "Erreur lors de la terminaison de l'evenement : " . $e->getMessage(), '../../view/crudEvenement/evenementRead.php?id=' . $idEvenement);
}
