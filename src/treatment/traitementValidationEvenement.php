<?php
require_once "../bdd/config.php";
require_once "../modele/ModeleEvenementUser.php";
require_once "../repository/EvenementUserRepository.php";
session_start();

function redirectWith(string $type, string $message, string $target): void
{
    $_SESSION[$type] = $message;
    session_write_close();
    header("Location: $target");
    exit();
}

$refUser = $_POST["refUser"];
$refEvenement = $_POST["ref_eve"];

if ($refUser===''||$refEvenement==='') {
    redirectWith('error', "Vous n'avez pas l'air connecté ", '../../view/evenements.php');
}
try {
    $evenementUser = new ModeleEvenementUser(array(
        "refUser" => $refUser,
        "refEvenement" => $refEvenement,
        "estSuperviseur" => 1
    ));

    $evenementUserRepository= new EvenementUserRepository();
    redirectWith('success', "L'evenement a bien été validé, N'oubliez c'est pour la vie", '../../view/crudEvenement/evenementRead?id='.$refEvenement.'.php');

    session_write_close();
} catch (PDOException $e) {
    redirectWith('error', "Erreur de l'inscription à l'evenement : " . $e->getMessage(), '../../view/evenements.php');
}

