
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once '../../vendor/autoload.php';

require_once '../bdd/config.php';


echo "<table>";
echo '<form action ="traitementVerificationEmail.php" method="post">';
echo    '<tr>';
echo        '<td><label for name = "email">Adresse mail pour reinitialiser mot de passe</label></td>';
echo        '<td> <input type ="text" name ="email"></td>';
echo        '<td><input type ="submit" value="Valider"></td>';

echo    '</tr>';
echo    '</form>'    ;
echo    '</table>'    ;



$config = new Config();
$pdo = $config->connexion();
$email = "";
if(isset($_POST['email'])){
    $email = $_POST['email'];
}

//1 -  Si cet email existe dans la base, un code est généré et stocké temporairement dans la base
$sqlEmail = $pdo -> prepare("SELECT * from utilisateur where email = ?");
$sqlEmail -> execute([$email]) ;
$ligne = $sqlEmail -> fetch();

if($ligne){
    echo "Cet email existe\n";
    $urlBase= "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
    $token = bin2hex(random_bytes(32));
    date_default_timezone_set('Europe/Paris');
    $expire_a = date("Y-m-d H:i:s", strtotime('+1 hour'));
    $sqlToken =$pdo->prepare( "INSERT INTO mdp_reset( token, expire_a, ref_user) VALUES (?,?,?)" );
    $sqlToken -> execute([$token,$expire_a,$ligne['id_user']]);
    $mail ="";
    $compte="";
    //Un email contenant un lien unique est envoyé à l’utilisateur.
    if($sqlToken){
        $lien=$urlBase ."../../../view/reinitialiserMdp.php/?token=".$token;
        //$lien="http://localhost//projet-lprs/view/reinitialiserMdp.php?token=".$token;
        $compte = $ligne['email'];
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