<?php
require_once "../bdd/config.php";
require_once "../repository/AvisRepository.php";
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

$idAvis = $_POST["idAvis"] ?? '';
$idEvenement = $_POST["refEvenement"] ?? '';
$idUser = $_SESSION['utilisateur']['id_user'];

if ($idAvis === '' || $idEvenement === '') {
    redirectWith('error', "Avis introuvable", '../../view/evenements.php');
}

try {
    $avisRepo = new AvisRepository();
    $avisRepo->deleteAvis($idAvis, $idUser);

    redirectWith('success', "Votre avis a bien ete supprime", '../../view/crudEvenement/evenementRead.php?id=' . $idEvenement);

} catch (PDOException $e) {
    redirectWith('error', "Erreur lors de la suppression de l'avis : " . $e->getMessage(), '../../view/crudEvenement/evenementRead.php?id=' . $idEvenement);
}
