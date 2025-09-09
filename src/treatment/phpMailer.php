<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';



require 'vendor/autoload.php'; // Ajustez selon votre méthode d'installation

$mail = new PHPMailer(true); // Activer les exceptions

// Configuration SMTP
$mail->isSMTP();
$mail->Host = 'live.smtp.mailtrap.io'; // Votre serveur SMTP
$mail->SMTPAuth = true;
$mail->Username = 'votre_nom_utilisateur'; // Votre nom d'utilisateur Mailtrap
$mail->Password = 'votre_mot_de_passe'; // Votre mot de passe Mailtrap
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

// Paramètres d'expéditeur et destinataire
$mail->setFrom('de@exemple.com', 'Nom Expéditeur');
$mail->addAddress('destinataire@exemple.com', 'Nom Destinataire');

// Envoi d'email en texte brut
$mail->isHTML(false); // Définir le format d'email en texte brut
$mail->Subject = 'Votre Sujet Ici';
$mail->Body = 'Ceci est le corps du message en texte brut';

// Envoyer l'email
if (!$mail->send()) {
    echo 'Le message n\'a pas pu être envoyé. Erreur : ' . $mail->ErrorInfo;
} else {
    echo 'Le message a été envoyé';
}