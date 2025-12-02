<?php
require_once "../modele/ModeleEvenement.php";
require_once "../bdd/config.php";
require_once "../repository/EvenementRepository.php";

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

$idEve = $_POST["ref_eve"];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWith('error', "Méthode invalide.", '../../view/crudEvenement/evenementUpdate.php?id=' . $idEve);
}

$titreEve = $_POST["titre_eve"];
$typeEve = $_POST["type_eve"];
$descEve = $_POST["desc_eve"];
$lieuEve = $_POST["lieu_eve"];
$elementEve = $_POST["element_eve"];
$nbPlace = $_POST["nb_place"];

if ($titreEve === '' || $typeEve === '' || $descEve === '' || $lieuEve === ''
    || $elementEve === '' || $nbPlace === '' || $idEve === '') {
    redirectWith('error', "Veuillez remplir tout les champs.", '../../view/crudEvenement/evenementUpdate.php?id=' . $idEve);
}
try {
    $evenement = new ModeleEvenement(array(
        "idEvenement" => $idEve,
        "titreEvenement" => $titreEve,
        "typeEvenement" => $typeEve,
        "descEvenement" => $descEve,
        "lieuEvenement" => $lieuEve,
        "elementEvenement" => $elementEve,
        "nbPlace" => $nbPlace
    ));
    $evenementRepository = new EvenementRepository();
    $refEvenement = $evenementRepository->updateEvenement($evenement);
    redirectWith('success', "L'evenement a bien été modifié", '../../view/crudEvenement/evenementRead.php?id=' . $idEve);
    session_write_close();
} catch (PDOException $e) {
    redirectWith('error', "Erreur de la modification d'evenement : " . $e->getMessage(), '../../view/crudEvenement/evenementUpdate.php?id=' . $idEve);
}

