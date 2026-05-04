<?php
require_once "../bdd/config.php";
require_once "../modele/ModeleAvis.php";
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
$note = $_POST["note"] ?? '';
$commentaire = trim($_POST["commentaire"] ?? '');
$idUser = $_SESSION['utilisateur']['id_user'];

if ($idAvis === '' || $idEvenement === '' || $note === '') {
    redirectWith('error', "Veuillez donner une note", '../../view/crudEvenement/evenementRead.php?id=' . $idEvenement);
}

$note = (int)$note;
if ($note < 1 || $note > 5) {
    redirectWith('error', "La note doit etre comprise entre 1 et 5", '../../view/crudEvenement/evenementAvisUpdate.php?id=' . $idEvenement);
}

if (mb_strlen($commentaire) > 2048) {
    redirectWith('error', "Le commentaire ne peut pas depasser 2048 caracteres", '../../view/crudEvenement/evenementAvisUpdate.php?id=' . $idEvenement);
}

try {
    $avisRepo = new AvisRepository();

    $avis = new ModeleAvis([
        "idAvis" => $idAvis,
        "note" => $note,
        "commentaire" => $commentaire ?: null,
        "dateAvis" => date('Y-m-d H:i:s'),
        "refUser" => $idUser,
        "refEvenement" => $idEvenement
    ]);

    $avisRepo->updateAvis($avis);

    redirectWith('success', "Votre avis a bien ete modifie", '../../view/crudEvenement/evenementRead.php?id=' . $idEvenement);

} catch (PDOException $e) {
    redirectWith('error', "Erreur lors de la modification de l'avis : " . $e->getMessage(), '../../view/crudEvenement/evenementRead.php?id=' . $idEvenement);
}
