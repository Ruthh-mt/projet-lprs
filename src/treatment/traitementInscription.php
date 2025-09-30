<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../bdd/config.php');
require_once('../repository/utilisateurRepository.php');

session_start();

function validatePassword(string $mdp) {
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
function handlePdfUpload(array $file): ?string
{
    if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $maxSize = 2 * 1024 * 1024;
    if (!isset($file['size']) || $file['size'] > $maxSize) {
        $_SESSION['error'] = "Le CV dépasse 2 Mo.";
        header("Location: ../../view/inscription.php");
        exit();
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);
    if ($mime !== 'application/pdf') {
        $_SESSION['error'] = "Le CV doit être un fichier PDF.";
        header("Location: ../../view/inscription.php");
        exit();
    }
    $uploadDir = __DIR__ . '/../../uploads';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            $_SESSION['error'] = "Impossible de créer le dossier d'upload.";
            header("Location: ../../view/inscription.php");
            exit();
        }
    }
    $destPath = $uploadDir . '/cv_' . bin2hex(random_bytes(8)) . '.pdf';

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        $_SESSION['error'] = "Échec de l'upload du CV.";
        header("Location: ../../view/inscription.php");
        exit();
    }
    return $destPath;
}
function getOrCreateFormationIdByName(PDO $pdo, string $nom): int
{
    $sel = $pdo->prepare("SELECT id_formation FROM formation WHERE nom = :nom");
    $sel->execute(['nom' => $nom]);
    $id = $sel->fetchColumn();
    if ($id) return (int)$id;
    $ins = $pdo->prepare("INSERT INTO formation (nom) VALUES (:nom)");
    $ins->execute(['nom' => $nom]);
    return (int)$pdo->lastInsertId();
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom    = htmlspecialchars(trim($_POST['nom'] ?? ''), ENT_QUOTES, 'UTF-8');
    $prenom = htmlspecialchars(trim($_POST['prenom'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email  = trim($_POST['email'] ?? '');
    $mdp    = $_POST['mdp'] ?? '';
    $mdp_confirm = $_POST['confirmation_mot_de_passe'] ?? '';
    $role   = $_POST['role'] ?? '';

    $classe      = $_POST['classe'] ?? null;
    $anneePromo  = $_POST['annee_promo'] ?? null;
    $specialite  = $_POST['specialite'] ?? null;
    $poste       = $_POST['poste'] ?? null;
    $raison      = $_POST['Raison'] ?? null;
    $cvFile      = $_FILES['cv'] ?? null;

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

    $repo = new UserRepository();
    $pdo  = (new Config())->connexion();

    if ($repo->getUserByEmail($email)) {
        $_SESSION['error'] = "Un utilisateur avec cet email existe déjà.";
        header("Location: ../../view/inscription.php");
        exit();
    }
    $hash = password_hash($mdp, PASSWORD_DEFAULT);

    $cvPath = null;
    if ($cvFile && isset($cvFile['tmp_name']) && $cvFile['error'] === UPLOAD_ERR_OK) {
        $cvPath = handlePdfUpload($cvFile);
    }
    try {
        $user = new UserModel([
            'nom'   => $nom,
            'prenom'=> $prenom,
            'email' => $email,
            'mdp'   => $hash,
            'role'  => $role
        ]);
        $repo->inscription($user);
        $stmt = $pdo->prepare("SELECT id_user FROM utilisateur WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $idUser = (int)$stmt->fetchColumn();

        if (!$idUser) {
            $_SESSION['error'] = "Impossible de récupérer l'identifiant de l'utilisateur créé.";
            header("Location: ../../view/inscription.php");
            exit();
        }
        switch ($role) {

            case 'Étudiant':
                $libelleFormation = $classe ?: 'Non précisé';
                $refFormation = getOrCreateFormationIdByName($pdo, $libelleFormation);

                $ins = $pdo->prepare("
                    INSERT INTO etudiant (ref_user, cv, annee_promo, ref_formation)
                    VALUES (:ref_user, :cv, :annee_promo, :ref_formation)
                ");
                $ins->execute([
                    'ref_user'      => $idUser,
                    'cv'            => $cvPath,
                    'annee_promo'   => $anneePromo ?: '',
                    'ref_formation' => $refFormation,
                ]);
                break;
            case 'Alumni':
                $ins = $pdo->prepare("
                    INSERT INTO alumni (ref_user, cv, annee_promo, poste, ref_fiche_entreprise)
                    VALUES (:ref_user, :cv, :annee_promo, :poste, :ref_fiche)
                ");
                $ins->execute([
                    'ref_user'    => $idUser,
                    'cv'          => $cvPath,
                    'annee_promo' => $anneePromo ?: '',
                    'poste'       => $poste,
                    'ref_fiche'   => null,
                ]);
                break;
            case 'Professeur':
                $ins = $pdo->prepare("
                    INSERT INTO professeur (ref_user, specialite)
                    VALUES (:ref_user, :specialite)
                ");
                $ins->execute([
                    'ref_user'   => $idUser,
                    'specialite' => $specialite ?: '',
                ]);
                break;
            case 'Partenaire':
                $ins = $pdo->prepare("
                    INSERT INTO partenaire (ref_user, cv, poste, ref_fiche_entreprise)
                    VALUES (:ref_user, :cv, :poste, :ref_fiche)
                ");
                $ins->execute([
                    'ref_user'  => $idUser,
                    'cv'        => $cvPath,
                    'poste'     => $poste ?: '',
                    'ref_fiche' => null,
                ]);
                break;
        }

        $_SESSION['success'] = "Inscription réussie !";
        header("Location: ../../view/connexion.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de l'inscription : " . $e->getMessage();
        header("Location: ../../view/inscription.php");
        exit();
    }
}
