<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Random\RandomException;

require_once '../../vendor/autoload.php';
require_once '../bdd/Config.php';
require_once "../modele/ModeleMdpReset.php";
require_once "../repository/MdpResetRepository.php";
require_once "../modele/ModeleUtilisateur.php";
require_once "../repository/UtilisateurRepository.php";
require_once "../security/PasswordHolder.php";

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

if(isset($_POST["email"])){
    $email = $_POST["email"];
    var_dump($email);

//1 -  Si cet email existe dans la base, un code est généré et stocké temporairement dans la base
    $repoUser = new UtilisateurRepository();
    $repoMdp = new MdpResetRepository();
    $ligne = $repoUser->getUserByEmail($email);
    var_dump($ligne);

    if ($ligne != null) {
        echo "Cet email existe\n";
        $basePath = dirname($_SERVER['PHP_SELF']);
        $basePath = dirname($basePath);
        $urlBase = "https://" . $_SERVER['HTTP_HOST'] . dirname($basePath);
        var_dump($urlBase);
        var_dump($_SERVER['HTTP_HOST']);
        var_dump($urlBase);

        try {
            $token = bin2hex(random_bytes(32));
        } catch (RandomException $e) {
            echo $e->getMessage();
        }
        date_default_timezone_set('Europe/Paris');
        $expireA = date("Y-m-d H:i:s", strtotime('+1 hour'));
        var_dump($expireA);
        $mdpReset = new ModeleMdpReset(array(
            "token" => $token, "expireA" => $expireA, "refUser" => $ligne["id_user"]));
        var_dump($mdpReset);
        $sqlToken = $repoMdp->createToken($mdpReset);

        var_dump($sqlToken);
        //Un email contenant un lien unique est envoyé à l’utilisateur.
        if ($sqlToken) {
            $lien = $urlBase . "/view/reinitialiserMdp.php?token=" . $mdpReset->getToken();

            $compte = $ligne['email'];
            var_dump($compte);
            var_dump($lien);
            try {
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'ltrsproject@gmail.com';
                $mail->Password = 'ihst qdia nvye moxf';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom("ltrsproject@gmail.com", 'Support');
                $mail->addAddress($compte, $ligne["nom"]);
                $mail->addreplyTo("ltrsproject@gmail.com", 'Support');
                $mail->isHTML();
                $mail->Subject = "Reinitialisation de votre mot de passe";
                $mail->Body = "<p>Bonjour, <br>
                            Il semblerait que vous avez fait une demande pour reinitialiser votre mot de passe. <br>  
                            Cliquez sur le lien pour reinitialiser votre mot de passe :
                <a href='$lien'>$lien</a>
                            </p>
                                <p>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>";
                $mail->AltBody = "Bonjour,\n\nCliquez sur le lien suivant pour réinitialiser votre mot de passe : $lien\n\n
                Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.";
                if ($mail->send()) {
                    echo 'to:' . $mail->getToAddresses()[0][0];
                    redirectWith('success', "Le mail a bien été envoyé. Verifiez vos mail.", '../../view/envoiEmailForm.php');
                } else {
                   redirectWith('error', "Le mail n'a pas pu etre envoyé. Erreur :" . $mail->ErrorInfo, '../../view/envoiEmailForm.php');
                }
            } catch (Exception $e) {
                redirectWith('error', "Le mail n'a pas pu etre envoyé. Erreur :" . $mail->ErrorInfo, '../../view/envoiEmailForm.php');
            }
        }

    } else {
        redirectWith('error', "Il semblerait que vous ne soyez pas inscrit ou que le mail entrée n'est pas le bon", '../../view/envoiEmailForm.php');
    }
} else {
    redirectWith('error', "Veuillez saisir une adress email", "../../view/envoiEmailForm.php");
}






