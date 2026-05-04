<?php
require_once "../bdd/config.php";
require_once "../modele/ModeleAvis.php";
require_once "../modele/ModeleEvenementUser.php";
require_once "../repository/AvisRepository.php";
require_once "../repository/EvenementUserRepository.php";
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

$idEvenement = $_POST["refEvenement"] ?? '';
$note = $_POST["note"] ?? '';
$commentaire = trim($_POST["commentaire"] ?? '');
$idUser = $_SESSION['utilisateur']['id_user'];

if ($idEvenement === '' || $note === '') {
    redirectWith('error', "Veuillez donner une note", '../../view/crudEvenement/evenementAvisCreate.php?id=' . $idEvenement);
}

$note = (int)$note;
if ($note < 1 || $note > 5) {
    redirectWith('error', "La note doit etre comprise entre 1 et 5", '../../view/crudEvenement/evenementAvisCreate.php?id=' . $idEvenement);
}

if (mb_strlen($commentaire) > 2048) {
    redirectWith('error', "Le commentaire ne peut pas depasser 2048 caracteres", '../../view/crudEvenement/evenementAvisCreate.php?id=' . $idEvenement);
}

try {
    $avisRepo = new AvisRepository();

    if ($avisRepo->hasUserAlreadyReviewed($idUser, $idEvenement)) {
        redirectWith('error', "Vous avez deja laisse un avis pour cet evenement", '../../view/crudEvenement/evenementRead.php?id=' . $idEvenement);
    }

    $evenementUserRepository = new EvenementUserRepository();
    $eveUser = new ModeleEvenementUser(["refUser" => $idUser, "refEvenement" => $idEvenement]);
    $estInscrit = $evenementUserRepository->verifDejaInscritEvenement($eveUser);

    if ($estInscrit) {
        redirectWith('error', "Vous devez avoir participe a cet evenement pour laisser un avis", '../../view/crudEvenement/evenementRead.php?id=' . $idEvenement);
    }

    $avis = new ModeleAvis([
        "note" => $note,
        "commentaire" => $commentaire ?: null,
        "dateAvis" => date('Y-m-d H:i:s'),
        "refUser" => $idUser,
        "refEvenement" => $idEvenement
    ]);

    $avisRepo->createAvis($avis);

    redirectWith('success', "Votre avis a bien ete enregistre, merci !", '../../view/crudEvenement/evenementRead.php?id=' . $idEvenement);

} catch (PDOException $e) {
    redirectWith('error', "Erreur lors de l'enregistrement de l'avis : " . $e->getMessage(), '../../view/crudEvenement/evenementRead.php?id=' . $idEvenement);
}
