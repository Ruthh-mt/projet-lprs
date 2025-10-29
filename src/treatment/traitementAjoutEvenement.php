<?php
require_once "../modele/ModeleEvenement.php";
require_once "../bdd/config.php";
require_once "../repository/EvenementRepository.php";
require_once "../modele/ModeleEvenementUser.php";
require_once "../repository/EvenementUserRepository.php";

session_start();
function redirectWith(string $type, string $message, string $target): void {
    $_SESSION[$type] = $message;
    session_write_close();
    header("Location: $target",$_SESSION[$type]);
    exit();
}
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirectWith('error', "Méthode invalide.", '../../view/crudEvenement/evenementCreate.php');
    }
    $refUser=$_POST["id_user"];
    $titreEve=$_POST["titre_eve"];
    $typeEve=$_POST["type_eve"];
    $descEve=$_POST["desc_eve"];
    $lieuEve=$_POST["lieu_eve"];
    $elementEve=$_POST["element_eve"];
    $nbPlace=$_POST["nb_plc"];

    if($titreEve==='' || $typeEve==='' || $descEve==='' || $lieuEve===''
        || $elementEve==='' || $nbPlace==='' ||$refUser===''){
       redirectWith('error', "Veuillez remplir tout les champs.", '../../view/crudEvenement/evenementCreate.php');
    }
    try {
        $evenement = new ModeleEvenement(array(
            "titreEvenement" => $titreEve,
            "typeEvenement" => $typeEve,
            "descEvenement" => $descEve,
            "lieuEvenement" => $lieuEve,
            "elementEvenement" => $elementEve,
            "nbPlace" => $nbPlace
        ));
        $evenementRepository = new EvenementRepository();
        $refEvenement=$evenementRepository->createEvenement($evenement);
        $evenementUser=new ModeleEvenementUser(array(
            "refUser" => $refUser,
            "refEvenement" => $refEvenement,
            "estSuperviseur"=>1
        ));
        $evenementUserRepository = new EvenementUserRepository();
        $evenementUserRepository->createEvenementUser($evenementUser);
        redirectWith('success',"L'evenement a bien été ajouté",'../../view/evenements.php');
        session_write_close();
    } catch (PDOException $e) {
        redirectWith('error', "Erreur de la creation d'evenement : " . $e->getMessage(), '../../view/crudEvenement/evenementCreate.php');
    }

