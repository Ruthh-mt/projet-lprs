<?php



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Random\RandomException;

require_once(__DIR__ . '/../security/PasswordHolder.php');


class EmailService
{
    private  $passwordHolder;
    private  $emailPassword;

    public function __construct(){
        $this->passwordHolder = new PasswordHolder();
        $this->emailPassword = $this->passwordHolder->getEmailPassword();
    }

    public function sendMail(string $to, string $subject, string $message, string $lien, string $nom){
        try {
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ltrsproject@gmail.com';
            $mail->Password = $this->emailPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom("ltrsproject@gmail.com", 'Support');
            $mail->addAddress($to, $nom);
            $mail->addreplyTo("ltrsproject@gmail.com", 'Support');
            $mail->isHTML();
            $mail->Subject = $subject;
            $mail->Body = $message . '\n' . $lien;

            $mail->AltBody = "Bonjour,\n\nCliquez sur le lien suivant pour réinitialiser votre mot de passe : $lien\n\n
                Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.";
            if ($mail->send()) {
                return true;
            } else {
                return false;
            }
        }catch (Exception $e){
            return $e->getMessage();
        }

    }

}