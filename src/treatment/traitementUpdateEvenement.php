<?php
require_once "../modele/Evenement.php";
require_once "../bdd/config.php";
require_once "../repository/EvenementRepository.php";



function redirectWith(string $type, string $message, string $target): void {
    $_SESSION[$type] = $message;
    session_write_close();
    header("Location: $target",$_SESSION[$type]);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWith('error', "Méthode invalide.", '../../view/crudEvenement/evenementUpdate.php');
}
$titreEve=$_POST["titre_eve"];
$typeEve=$_POST["type_eve"];
$descEve=$_POST["desc_eve"];
$lieuEve=$_POST["lieu_eve"];
$elementEve=$_POST["element_eve"];
$nbPlace=$_POST["nb_place"];
$idEve=$_POST["ref_eve"];

if($titreEve==='' || $typeEve==='' || $descEve==='' || $lieuEve===''
    || $elementEve==='' || $nbPlace==='' ||$idEve===''){
    redirectWith('error', "Veuillez remplir tout les champs.", '../../view/crudEvenement/evenementCreate.php');
}
try {
    $evenement = new Evenement(array(
        "idEvenement" => $idEve,
        "titreEvenement" => $titreEve,
        "typeEvenement" => $typeEve,
        "descEvenement" => $descEve,
        "lieuEvenement" => $lieuEve,
        "elementEvenement" => $elementEve,
        "nbPlace" => $nbPlace
    ));
    $evenementRepository = new EvenementRepository();
    $refEvenement=$evenementRepository->updateEvenement($evenement);
    redirectWith('success',"L'evenement a bien été modifié",'../../view/crudEvenement/afficherEvenement?id='.$refEvenement.'.php');
} catch (PDOException $e) {
    redirectWith('error', "Erreur de la creation d'evenement : " . $e->getMessage(), '../../view/crudEvenement/evenementCreate.php');
}

