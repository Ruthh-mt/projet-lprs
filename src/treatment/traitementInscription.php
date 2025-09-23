<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../bdd/config.php');
require_once('../repository/userRepository.php');

session_start();

function validatePassword($mdp) {
     if (strlen($mdp) < 12) {
          return "Le mot de passe doit contenir au moins 12 caractères.";
     }
     if (!preg_match('/[A-Z]/', $mdp)) {
          return "Le mot de passe doit contenir au moins une majuscule.";
     }
     if (!preg_match('/[a-z]/', $mdp)) {
          return "Le mot de passe doit contenir au moins une minuscule.";
     }
     if (!preg_match('/[0-9]/', $mdp)) {
          return "Le mot de passe doit contenir au moins un chiffre.";
     }
     if (!preg_match('/[\W_]/', $mdp)) {
          return "Le mot de passe doit contenir au moins un caractère spécial.";
     }
     return true;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
     $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
     $prenom = htmlspecialchars(trim($_POST['prenom'] ?? ''));
     $email = trim($_POST['email'] ?? '');
     $mdp = $_POST['mdp'] ?? '';
     $mdp_confirm = $_POST['confirmation_mot_de_passe'] ?? '';
     $role = $_POST['role'] ?? '';

     $classe = $_POST['classe'] ?? null;
     $annee_promo = $_POST['annee_promo'] ?? null;
     $cv = $_FILES['cv'] ?? null;
     $specialite = $_POST['specialite'] ?? null;
     $poste = $_POST['poste'] ?? null;
     $raison = $_POST['Raison'] ?? null;

     if (empty($nom) || empty($prenom) || empty($email) || empty($mdp) || empty($mdp_confirm) || empty($role)) {
          $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis.";
          header("Location: ../../view/inscription.php");
          exit();
     }

     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $_SESSION['error'] = "Adresse email invalide.";
          header("Location: ../../view/inscription.php");
          exit();
     }

     $pwdValidation = validatePassword($mdp);
     if ($pwdValidation !== true) {
          $_SESSION['error'] = $pwdValidation;
          header("Location: ../../view/inscription.php");
          exit();
     }

     if ($mdp !== $mdp_confirm) {
          $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
          header("Location: ../../view/inscription.php");
          exit();
     }

     $config = new Config();
     $connexion = $config->connexion();
     $repo = new UserRepository($connexion);

     if ($repo->getUserByEmail($email)) {
          $_SESSION['error'] = "Un utilisateur avec cet email existe déjà.";
          header("Location: ../../view/inscription.php");
          exit();
     }

     $hash = password_hash($mdp, PASSWORD_DEFAULT);

     $userData = [
          'nom' => $nom,
          'prenom' => $prenom,
          'email' => $email,
          'mot_de_passe' => $hash,
          'role' => $role
     ];

     if ($role === "Étudiant" || $role === "Alumni") {
          $userData['classe'] = $classe;
          $userData['annee_promo'] = $annee_promo;
          if ($cv && $cv['error'] === UPLOAD_ERR_OK) {
               $cvPath = '../../uploads/cv_' . uniqid() . '.pdf';
               move_uploaded_file($cv['tmp_name'], $cvPath);
               $userData['cv'] = $cvPath;
          }
     } elseif ($role === "Professeur") {
          $userData['specialite'] = $specialite;
     } elseif ($role === "Partenaire") {
          $userData['poste'] = $poste;
          $userData['raison'] = $raison;
     }

     try {
          $repo->ajouterUtilisateur(new UserRepository($userData));
          $_SESSION['success'] = "Inscription réussie !";
          header("Location: ../../view/inscription.php");
          exit();
     } catch (PDOException $e) {
          $_SESSION['error'] = "Erreur lors de l'inscription : " . $e->getMessage();
          header("Location: ../../view/inscription.php");
          exit();
     }
}
?>
