<?php

require_once "../modele/Evenement.php";
require_once "../bdd/config.php";
require_once "../repository/EvenementRepository.php";
require_once "../modele/EvenementUser.php";
require_once "../repository/EvenementUserRepository.php";


function redirectWith(string $type, string $message, string $target): void
{
    $_SESSION[$type] = $message;
    session_write_close();
    header("Location: $target");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWith('error', "Méthode invalide.", '../../view/crudEvenement/evenementCreate.php');
}
$refUser = $_POST["refUser"];
$refEvenement = $_POST["ref_eve"];

if ($refUser===''||$refEvenement==='') {
    redirectWith('error', "Veuillez remplir tout les champs.", '../../view/crudEvenement/evenementCreate.php');
}
try {
    $evenementUser = new EvenementUser(array(
        "refUser" => $refUser,
        "refEvenement" => $refEvenement
    ));
    $evenementUserRepository= new EvenementUserRepository();
    $evenementUserRepository->inscriptionEvenementUser($evenementUser);
    redirectWith('success', "Vous avez bien été inscrit", '../../view/evenements.php');
} catch (PDOException $e) {
    redirectWith('error', "Erreur de la creation d'evenement : " . $e->getMessage(), '../../view/crudEvenement/evenementCreate.php');
}

