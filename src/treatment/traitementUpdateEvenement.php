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

$idEve = $_POST["refEve"];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWith('error', "Méthode invalide.", '../../view/crudEvenement/evenementUpdate.php?id=' . $idEve);
}

$titreEve = $_POST["titreEve"];
$typeEve = $_POST["typeEve"];
$descEve = $_POST["descEve"];
$lieuEve = $_POST["lieuEve"];
$elementEve = $_POST["elementEve"];
$nbPlace = $_POST["nbPlace"];
$status = $_POST["status"];
$dateHeureEvenement = $_POST["dateHeureEvenement"];
if ($titreEve === '' || $typeEve === '' || $descEve === '' || $lieuEve === ''
    || $elementEve === '' || $nbPlace === '' || $idEve === '' ||  $dateHeureEvenement==='') {
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
        "nbPlace" => $nbPlace,
        "dateHeureEvenement" => $dateHeureEvenement,
        "status" => $status,
    ));
    $evenementRepository = new EvenementRepository();
    $refEvenement = $evenementRepository->updateEvenement($evenement);
    redirectWith('success', "L'evenement a bien été modifié", '../../view/crudEvenement/evenementRead.php?id=' . $idEve);
    session_write_close();

} catch (PDOException $e) {
    redirectWith('error', "Erreur de la modification d'evenement : " . $e->getMessage(), '../../view/crudEvenement/evenementUpdate.php?id=' . $idEve);
}

