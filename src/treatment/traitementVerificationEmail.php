
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Random\RandomException;

require_once '../../vendor/autoload.php';
require_once '../bdd/config.php';
require_once "../modele/ModeleMdpReset.php";
require_once "../repository/MdpResetRepository.php";
require_once "../modele/ModeleUtilisateur.php";
require_once "../repository/UtilisateurRepository.php";


echo "<table>";
echo '<form action ="traitementVerificationEmail.php" method="post">';
echo    '<tr>';
echo        '<td><label for="email">Adresse mail pour reinitialiser mot de passe</label></td>';
echo        '<td> <input id="email" type ="text" name ="email"></td>';
echo        '<td><input type ="submit" value="Valider"></td>';

echo    '</tr>';
echo    '</form>'    ;
echo    '</table>'    ;



$config = new Config();
$email = "";
if(isset($_POST['email'])){
    $email = $_POST['email'];
}

//1 -  Si cet email existe dans la base, un code est généré et stocké temporairement dans la base
$repoUser= new UtilisateurRepository();
$repoMdp= new MdpResetRepository();
$ligne = $repoUser->getUserByEmail($email);

if($ligne){
    echo "Cet email existe\n";
    $basePath = dirname($_SERVER['PHP_SELF']);
    $basePath = dirname($basePath);
    $urlBase= "http://".$_SERVER['HTTP_HOST'].dirname($basePath);
    var_dump($urlBase);
    var_dump($_SERVER['HTTP_HOST']);
    var_dump($urlBase);

    try {
        $token = bin2hex(random_bytes(32));
    } catch (RandomException $e) {
        ECHO $e->getMessage();
    }
    date_default_timezone_set('Europe/Paris');
    $expire_a = date("Y-m-d H:i:s", strtotime('+1 hour'));
    $mdpReset=new ModeleMdpReset(["token"=>$token, "expire_a"=>$expire_a,"refUser"=>$ligne["id_user"]]);
    $sqlToken =$repoMdp->createToken($mdpReset);
    $mail="";
    //Un email contenant un lien unique est envoyé à l’utilisateur.
    if($sqlToken){
        $lien=$urlBase ."/view/reinitialiserMdp.php?token=".$mdpReset->getToken();

        $compte = $ligne['email'];
        var_dump($lien);
        try {
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ltrsproject@gmail.com';
            $mail->Password = 'xbxp ihqx ptym ummb';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom("ltrsproject@gmail.com", 'Support');
            $mail->addAddress($compte,$ligne["nom"]);
            $mail->addreplyTo("ltrsproject@gmail.com", 'Support');
            $mail->isHTML();
            $mail->Subject = "Reinitialisation de votre mot de passe";
            $mail->Body = "<p>Bonjour,</p>
                <p>Cliquez sur le lien pour reinitialiser votre mot de passe :</p> 
                <p><a href='$lien'>$lien</a></p>
                                <p>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>";
            $mail->AltBody = "Bonjour,\n\nCliquez sur le lien suivant pour réinitialiser votre mot de passe : $lien\n\n
                Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.";
            if($mail->send()){
                echo 'to:'.$mail->getToAddresses()[0][0];
               header("location: ../../view/messageConfirmation.php");
            }else{
                echo"le message n'a pas pu etre envoyer(".$mail->ErrorInfo.")";
            }
        }catch (Exception $e){
            echo"Erreur lors de l'envoi de votre mail : (".$mail->ErrorInfo.")";
        }
    }
}





?>