<?php
global $e;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require '../../vendor/autoload.php';
session_start();
require_once('../bdd/config.php');
require_once('../repository/PostulerRepository.php');

$pdo = (new Config())->connexion();

// Vérification session
if (!isset($_SESSION['utilisateur'])) {
    echo "<script>alert('Veuillez vous connecter pour postuler.'); window.location.href='../../view/connexion.php';</script>";
    exit;
}

// Récupération des infos utilisateur depuis la session
$utilisateur = $_SESSION['utilisateur'];
$ref_user = (int) $utilisateur['id_user'];
$nom_user = $utilisateur['nom'];
$prenom_user = $utilisateur['prenom'];
$ref_offre = (int) ($_POST['ref_offre'] ?? 0);
$lettre = $_POST['lettre'] ?? '';
$est_accepte = 1;

$postulerRepo = new PostulerRepository();

// Vérification des champs de base
if (empty($ref_offre) || empty($lettre)) {
    echo "<script>alert('Veuillez remplir tous les champs obligatoires.'); window.history.back();</script>";
    exit;
}

// Vérifie si l'utilisateur a déjà postulé à cette offre
$verif = $pdo->prepare("SELECT COUNT(*) FROM postuler WHERE ref_user = ? AND ref_offre = ?");
$verif->execute([$ref_user, $ref_offre]);
if ($verif->fetchColumn() > 0) {
    echo "<script>alert('Vous avez déjà postulé à cette offre.'); window.location.href='../../view/emplois.php';</script>";
    exit;
}

// -------------------------------- Gestion du CV -----------------------------------------
$chemin_telechargement = __DIR__ . '/telechargement/candidatures/';

if (!is_dir($chemin_telechargement)) {
    mkdir($chemin_telechargement, 0777, true);
}

$lien_cv = null;

// Normalisation nom/prénom
$nom_simplifie = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($nom_user));
$prenom_simplifie = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($prenom_user));

if (!empty($_FILES['cv']['name'])) {

    // SI L'EXTENSION EST ABSENTE → ON FORCE PDF
    $extension = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
    if ($extension === "" || $extension === null) {
        $extension = "pdf";
    }

    $cvTmp = $_FILES['cv']['tmp_name'];
    $nom_cv = "cv_{$nom_simplifie}_{$prenom_simplifie}." . $extension;
    $cvDest = $chemin_telechargement . $nom_cv;

    if (move_uploaded_file($cvTmp, $cvDest)) {
        $lien_cv = "/telechargement/candidatures/" . $nom_cv;
    }
}


// -------------------------------- Insertion BDD -----------------------------------------

$sql = $pdo->prepare("
    INSERT INTO postuler (ref_user, ref_offre, motivation, est_accepte)
    VALUES (?,  ?, ?, ?)
");
$candidat = $postulerRepo ->getCandidat($ref_user);
$nomCandidat = $candidat['nom']." ".$candidat['prenom'];
$ok = $sql->execute([$ref_user, $ref_offre, $lettre, $est_accepte,]);

if ($ok) {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'rom.quashie@gmail.com';
    $mail->Password = 'fbzo qrgr fvut ytmg';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom("rom.quashie@gmail.com", 'Support');
    $mail->addAddress("rom.quashie@gmail.com", $nomCandidat);
    $mail->addreplyTo("rom.quashie@gmail.com", 'Support');

    $mail->isHTML();
    $mail->Subject = "Candidature offre d'emplois";
    $mail->Body = "<?php echo nomCandidat ?> <p> vient de postuler au poste de </p> <?php echo titre ?>";
    $mail->AltBody = "";

    if ($mail->send()) {
        echo 'to:' . $mail->getToAddresses()[0][0];
        echo "<script>alert('Votre candidature a été envoyée avec succès !'); window.location.href='../../view/redirection_postuler.php';</script>";

    } else {
        echo "le message n'a pas pu etre envoyer(" . $mail->ErrorInfo . ")";


        exit;
    }
}
?>
