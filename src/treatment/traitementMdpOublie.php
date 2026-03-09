<?php


use Random\RandomException;

require_once '../../vendor/autoload.php';
require_once '../bdd/Config.php';
require_once "../modele/ModeleMdpReset.php";
require_once "../repository/MdpResetRepository.php";
require_once "../modele/ModeleUtilisateur.php";
require_once "../repository/UtilisateurRepository.php";
require_once "../service/EmailService.php";

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
        $emailService = new EmailService();
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
                /* Coucou Mon equipe favorite. Alors cette ligne, elle permet d'envoyer un mail j'ai rendu sa un peut mieux comme sa on a
                juste besoin d'appeler la methode dans EmailService.php
                */
                $succes=$emailService->sendMail($compte,"Reinitialisation de mot de passe","<p>Bonjour, <br>
                            Il semblerait que vous avez fait une demande pour reinitialiser votre mot de passe. <br>  
                            Cliquez sur le lien pour reinitialiser votre mot de passe :
                <a href='$lien'>$lien</a>
                            </p>
                                <p>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>", $lien,$ligne["nom"]);
                if ($succes) {
                    redirectWith('success', "Le mail a été envoyé. Verifier votre boite mail" , '../../view/envoiEmailForm.php');
                    session_write_close();

                }else{
                    redirectWith('error', "Le mail n'a pas pu etre envoyé. Erreur :" . $email->ErrorInfo, '../../view/envoiEmailForm.php');
                }
            } catch (Exception $e) {
                redirectWith('error', "Le mail n'a pas pu etre envoyé. Erreur :" . $email->ErrorInfo, '../../view/envoiEmailForm.php');
            }
        }

    }


}






