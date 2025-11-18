<?php
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../bdd/config.php';
require_once __DIR__ . '/../repository/UtilisateurRepository.php';
require_once __DIR__ . '/../repository/EtudiantRepository.php';
require_once __DIR__ . '/../repository/AlumniRepository.php';
require_once __DIR__ . '/../repository/ProfesseurRepository.php';
require_once __DIR__ . '/../repository/PartenaireRepository.php';

function redirectWith(string $type, string $message, string $target): void {
     $_SESSION[$type] = $message;
     session_write_close();
     header("Location: $target");
     exit();
}
function clean_str(?string $v): string {
     return htmlspecialchars(trim((string)$v), ENT_QUOTES, 'UTF-8');
}
function norm_role(string $role): string {
     $r = mb_strtolower($role, 'UTF-8');
     return match ($r) {
          'etudiant','étudiant' => 'Étudiant',
          'alumni'              => 'Alumni',
          'professeur'          => 'Professeur',
          'partenaire'          => 'Partenaire',
          'gestionnaire'        => 'Gestionnaire',
          default               => $role,
     };
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
     redirectWith('error', "Méthode invalide.", '../../view/account/accountUpdate.php');
}
if (empty($_SESSION['utilisateur'])) {
     redirectWith('error', "Session expirée, veuillez vous reconnecter.", '../../view/connexion.php');
}

$pdo = (new Config())->connexion();

$sessionUser = $_SESSION['utilisateur'];
$id_user     = (int)($sessionUser['id_user'] ?? 0);
if ($id_user <= 0) {
     redirectWith('error', "Utilisateur invalide.", '../../view/connexion.php');
}

$nom    = clean_str($_POST['nom']    ?? $sessionUser['nom']    ?? '');
$prenom = clean_str($_POST['prenom'] ?? $sessionUser['prenom'] ?? '');
$email  = clean_str($_POST['email']  ?? $sessionUser['email']  ?? '');
$roleIn = $_POST['role'] ?? ($sessionUser['role'] ?? '');
$role   = norm_role($roleIn);

if ($nom === '' || $prenom === '' || $email === '') {
     redirectWith('error', "Nom, prénom et email sont obligatoires.", '../../view/account/accountUpdate.php');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
     redirectWith('error', "Adresse email invalide.", '../../view/account/accountUpdate.php');
}

try {
     $uRepo = new utilisateurRepository();
     $exists = $uRepo->getUserByEmail($email);
     if ($exists && (int)$exists['id_user'] !== $id_user) {
          redirectWith('error', "Cette adresse email est déjà utilisée par un autre compte.", '../../view/account/accountUpdate.php');
     }
} catch (Throwable $e) {
     redirectWith('error', "Vérification email impossible : ".$e->getMessage(), '../../view/account/accountUpdate.php');
}

$cvPublicPath = null;
$avatarPublicPath = null;

// Upload du CV (inchangé)
if (!empty($_FILES['cv']['name'])) {
    $err = $_FILES['cv']['error'] ?? UPLOAD_ERR_OK;
    if ($err !== UPLOAD_ERR_OK) {
        redirectWith('error', "Échec de l'upload du CV (code $err).", '../../view/account/accountUpdate.php');
    }
    $ext = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        redirectWith('error', "Le CV doit être un fichier PDF.", '../../view/account/accountUpdate.php');
    }

    $publicDir = realpath(__DIR__ . '/../../public');
    if ($publicDir === false) { $publicDir = __DIR__ . '/../../public'; }

    $cvDir = rtrim($publicDir, DIRECTORY_SEPARATOR) . '/uploads/cv';
    if (!is_dir($cvDir) && !mkdir($cvDir, 0775, true)) {
        redirectWith('error', "Impossible de créer le dossier d'upload CV.", '../../view/account/accountUpdate.php');
    }

    $filename = 'user' . $id_user . '.pdf';
    $destFs   = $cvDir . DIRECTORY_SEPARATOR . $filename;
    if (!move_uploaded_file($_FILES['cv']['tmp_name'], $destFs)) {
        redirectWith('error', "Impossible d'enregistrer le CV sur le serveur.", '../../view/account/accountUpdate.php');
    }

    $cvPublicPath = '/uploads/cv/' . $filename;
}

// Upload de la photo de profil
if (!empty($_FILES['avatar']['name'])) {
    $err = $_FILES['avatar']['error'] ?? UPLOAD_ERR_OK;
    if ($err !== UPLOAD_ERR_OK) {
        redirectWith('error', "Échec de l'upload de la photo de profil (code $err).", '../../view/account/accountUpdate.php');
    }

    $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
    $allowedImg = ['jpg','jpeg','png','gif','webp'];
    if (!in_array($ext, $allowedImg, true)) {
        redirectWith('error', "La photo de profil doit être une image (jpg, jpeg, png, gif, webp).", '../../view/account/accountUpdate.php');
    }

    $publicDir = realpath(__DIR__ . '/../../public');
    if ($publicDir === false) { $publicDir = __DIR__ . '/../../public'; }

    $avatarDir = rtrim($publicDir, DIRECTORY_SEPARATOR) . '/uploads/avatar';
    if (!is_dir($avatarDir) && !mkdir($avatarDir, 0775, true)) {
        redirectWith('error', "Impossible de créer le dossier d'upload avatar.", '../../view/account/accountUpdate.php');
    }

    $filename = 'user' . $id_user . '.' . $ext;
    $destFs   = $avatarDir . DIRECTORY_SEPARATOR . $filename;
    if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $destFs)) {
        redirectWith('error', "Impossible d'enregistrer la photo de profil sur le serveur.", '../../view/account/accountUpdate.php');
    }

    $avatarPublicPath = '/uploads/avatar/' . $filename;
}

$annee_promo          = null;
$ref_formation        = null;
$specialite           = null;
$poste                = null;
$ref_fiche_entreprise = null;

switch ($role) {
     case 'Étudiant':
          $annee_promo   = clean_str($_POST['annee_promo'] ?? '');
          $ref_formation = $_POST['ref_formation'] ?? '';
          if ($annee_promo === '' || $ref_formation === '') {
               redirectWith('error', "Pour le rôle Étudiant, 'année de promo' et 'réf. formation' sont obligatoires.", '../../view/account/accountUpdate.php');
          }
          if (!ctype_digit($ref_formation)) {
               redirectWith('error', "La référence de formation doit être un identifiant numérique.", '../../view/account/accountUpdate.php');
          }
          $ref_formation = (int)$ref_formation;

          $chk = $pdo->prepare("SELECT 1 FROM formation WHERE id_formation = :id");
          $chk->execute(['id' => $ref_formation]);
          if (!$chk->fetchColumn()) {
               redirectWith('error', "La formation #$ref_formation n'existe pas.", '../../view/account/accountUpdate.php');
          }
          break;

     case 'Alumni':
          $annee_promo          = clean_str($_POST['annee_promo'] ?? '');
          $poste                = clean_str($_POST['poste'] ?? '');
          $ref_fiche_entreprise = $_POST['ref_fiche_entreprise'] ?? null;
          if ($annee_promo === '') {
               redirectWith('error', "Pour le rôle Alumni, 'année de promo' est obligatoire.", '../../view/account/accountUpdate.php');
          }
          if ($ref_fiche_entreprise === '' ) $ref_fiche_entreprise = null;
          if ($ref_fiche_entreprise !== null) {
               if (!ctype_digit((string)$ref_fiche_entreprise)) {
                    redirectWith('error', "L'entreprise doit être un identifiant numérique.", '../../view/account/accountUpdate.php');
               }
               $ref_fiche_entreprise = (int)$ref_fiche_entreprise;
               $chk = $pdo->prepare("SELECT 1 FROM fiche_entreprise WHERE id_fiche_entreprise = :id");
               $chk->execute(['id' => $ref_fiche_entreprise]);
               if (!$chk->fetchColumn()) {
                    redirectWith('error', "La fiche entreprise #$ref_fiche_entreprise n'existe pas.", '../../view/account/accountUpdate.php');
               }
          }
          break;

     case 'Professeur':
          $specialite = clean_str($_POST['specialite'] ?? '');
          if ($specialite === '') {
               redirectWith('error', "Pour le rôle Professeur, 'spécialité' est obligatoire.", '../../view/account/accountUpdate.php');
          }
          break;

     case 'Partenaire':
          $poste                = clean_str($_POST['poste'] ?? '');
          $ref_fiche_entreprise = $_POST['ref_fiche_entreprise'] ?? null;
          if ($poste === '') {
               redirectWith('error', "Pour le rôle Partenaire, 'poste' est obligatoire.", '../../view/account/accountUpdate.php');
          }
          if ($ref_fiche_entreprise === '' ) $ref_fiche_entreprise = null;
          if ($ref_fiche_entreprise !== null) {
               if (!ctype_digit((string)$ref_fiche_entreprise)) {
                    redirectWith('error', "La réf. fiche entreprise doit être un identifiant numérique.", '../../view/account/accountUpdate.php');
               }
               $ref_fiche_entreprise = (int)$ref_fiche_entreprise;
               $chk = $pdo->prepare("SELECT 1 FROM fiche_entreprise WHERE id_fiche_entreprise = :id");
               $chk->execute(['id' => $ref_fiche_entreprise]);
               if (!$chk->fetchColumn()) {
                    redirectWith('error', "La fiche entreprise #$ref_fiche_entreprise n'existe pas.", '../../view/account/accountUpdate.php');
               }
          }
          break;

     case 'Gestionnaire':
     default:
          break;
}

$pdo->beginTransaction();

try {
     $stmt = $pdo->prepare("SELECT id_user, mdp, role, ref_validateur FROM utilisateur WHERE id_user = :id");
     $stmt->execute(['id' => $id_user]);
     $current = $stmt->fetch(PDO::FETCH_ASSOC);
     if (!$current) {
          throw new RuntimeException("Utilisateur introuvable.");
     }
    $sqlU = "UPDATE utilisateur 
              SET nom = :nom, prenom = :prenom, email = :email";
    $paramsU = [
        'nom'    => $nom,
        'prenom' => $prenom,
        'email'  => $email,
        'id'     => $id_user,
    ];

    if ($avatarPublicPath !== null) {
        $sqlU .= ", avatar = :avatar";
        $paramsU['avatar'] = $avatarPublicPath;
    }

    $sqlU .= " WHERE id_user = :id";

    $upd = $pdo->prepare($sqlU);
    $upd->execute($paramsU);
     switch ($role) {
          case 'Étudiant': {
               $repo = new etudiantRepository();
               $exists = $repo->findByUserId($id_user);

               $payload = [
                    'ref_user'      => $id_user,
                    'cv'            => $cvPublicPath ?? ($sessionUser['cv'] ?? null),
                    'annee_promo'   => $annee_promo,
                    'ref_formation' => $ref_formation,
               ];
               if ($exists) $repo->update($id_user, $payload);
               else         $repo->insert($payload);
               break;
          }
          case 'Alumni': {
               $repo = new alumniRepository();
               $exists = $repo->findByUserId($id_user);

               $payload = [
                    'ref_user'             => $id_user,
                    'cv'                   => $cvPublicPath ?? ($sessionUser['cv'] ?? null),
                    'annee_promo'          => $annee_promo,
                    'poste'                => ($poste !== '' ? $poste : null),
                    'ref_fiche_entreprise' => $ref_fiche_entreprise,
               ];
               if ($exists) $repo->update($id_user, $payload);
               else         $repo->insert($payload);
               break;
          }
          case 'Professeur': {
               $repo = new professeurRepository();
               $exists = $repo->findByUserId($id_user);

               $payload = [
                    'ref_user'   => $id_user,
                    'specialite' => $specialite,
               ];
               if ($exists) $repo->update($id_user, $payload);
               else         $repo->insert($payload);
               break;
          }
          case 'Partenaire': {
               $repo = new partenaireRepository();
               $exists = $repo->findByUserId($id_user);

               $payload = [
                    'ref_user'             => $id_user,
                    'cv'                   => $cvPublicPath ?? ($sessionUser['cv'] ?? null),
                    'poste'                => $poste,
                    'ref_fiche_entreprise' => $ref_fiche_entreprise,
               ];
               if ($exists) $repo->update($id_user, $payload);
               else         $repo->insert($payload);
               break;
          }
          case 'Gestionnaire':
          default:
               break;
     }

     $pdo->commit();

    $_SESSION['utilisateur']['nom']    = $nom;
    $_SESSION['utilisateur']['prenom'] = $prenom;
    $_SESSION['utilisateur']['email']  = $email;
    $_SESSION['utilisateur']['role']   = $role;
    if ($cvPublicPath)      { $_SESSION['utilisateur']['cv']     = $cvPublicPath; }
    if ($avatarPublicPath)  { $_SESSION['utilisateur']['avatar'] = $avatarPublicPath; }
    $avatarPublicPath = '/uploads/avatar/' . $filename;
    $cvPublicPath = '/uploads/cv/' . $filename;

     switch ($role) {
          case 'Étudiant':
               $_SESSION['utilisateur']['annee_promo']   = $annee_promo;
               $_SESSION['utilisateur']['ref_formation'] = $ref_formation;
               break;
          case 'Alumni':
               $_SESSION['utilisateur']['annee_promo']          = $annee_promo;
               $_SESSION['utilisateur']['poste']                = ($poste !== '' ? $poste : null);
               $_SESSION['utilisateur']['ref_fiche_entreprise'] = $ref_fiche_entreprise;
               break;
          case 'Professeur':
               $_SESSION['utilisateur']['specialite'] = $specialite;
               break;
          case 'Partenaire':
               $_SESSION['utilisateur']['poste']                = $poste;
               $_SESSION['utilisateur']['ref_fiche_entreprise'] = $ref_fiche_entreprise;
               break;
     }

     $_SESSION['success'] = "Profil mis à jour.";
     session_write_close();
     header("Location: ../../view/account/accountRead.php");
     exit();

} catch (Throwable $e) {
     if ($pdo->inTransaction()) { $pdo->rollBack(); }
     redirectWith('error', "Erreur lors de la mise à jour : " . $e->getMessage(), '../../view/account/accountUpdate.php');
}