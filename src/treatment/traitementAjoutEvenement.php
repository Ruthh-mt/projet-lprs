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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWith('error', "Méthode invalide.", '../../view/crudEvenement/evenementCreate.php');
}
$refUser = $_POST["idUser"];
$role = $_POST["role"];
$titreEve = $_POST["titreEve"];
$typeEve = $_POST["typeEve"];
$descEve = $_POST["descEve"];
$lieuEve = $_POST["lieuEve"];
$elementEve = $_POST["elementEve"];
$nbPlace = $_POST["nbPlc"];
$status = $_POST["status"];
$dateHeureEvenement = $_POST["dateHeureEvenement"];


if ($titreEve === '' || $typeEve === '' || $descEve === '' || $lieuEve === ''
    || $elementEve === '' || $nbPlace === '' || $refUser === '' || $role === '' || $status === ''|| $dateHeureEvenement==='') {
    redirectWith('error', "Veuillez remplir tout les champs.", '../../view/crudEvenement/evenementCreate.php');
}
try {


    if ($role == "Étudiant") {

        $evenement = new ModeleEvenement(array(
            "titreEvenement" => $titreEve,
            "typeEvenement" => $typeEve,
            "descEvenement" => $descEve,
            "lieuEvenement" => $lieuEve,
            "elementEvenement" => $elementEve,
            "nbPlace" => $nbPlace,
            "dateHeureEvenement" =>$dateHeureEvenement,
            "status" => $status,
            "estValide" => 0
        ));
    } else {
        $evenement = new ModeleEvenement(array(
            "titreEvenement" => $titreEve,
            "typeEvenement" => $typeEve,
            "descEvenement" => $descEve,
            "lieuEvenement" => $lieuEve,
            "elementEvenement" => $elementEve,
            "nbPlace" => $nbPlace,
            "dateHeureEvenement" => $dateHeureEvenement,
            "status" => $status,
            "estValide" => 1
        ));

    }
    $evenementRepository = new EvenementRepository();
    $refEvenement = $evenementRepository->createEvenement($evenement);
    $evenementUser = new ModeleEvenementUser(array(
        "refUser" => $refUser,
        "refEvenement" => $refEvenement,
        "estSuperviseur" => 1
    ));
    $evenementUserRepository = new EvenementUserRepository();
    $evenementUserRepository->createEvenementUser($evenementUser);
    if ($role == "Étudiant") {
        redirectWith('success', "L'evenement a bien été ajouté, il est en attende d'une validation par un professeur", '../../view/evenements.php');
    } else {
        redirectWith('success', "L'evenement a bien été ajouté", '../../view/evenements.php');
    }
    session_write_close();

} catch (PDOException $e) {
    redirectWith('error', "Erreur de la creation d'evenement : " . $e->getMessage(), '../../view/crudEvenement/evenementCreate.php');
}
