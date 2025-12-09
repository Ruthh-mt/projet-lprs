<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

require_once('../bdd/config.php');
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
function clean(string $v): string {
    return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWith('error', "Méthode invalide.", '../../view/connexion.php');
}
$email        = clean($_POST['email'] ?? '');
$motDePasse   = $_POST['mot_de_passe'] ?? '';

if ($email === '' || $motDePasse === '') {
    redirectWith('error', "Veuillez renseigner l'email et le mot de passe.", '../../view/connexion.php');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectWith('error', "Adresse email invalide.", '../../view/connexion.php');
}
try {
    $pdo = (new Config())->connexion();

    $sql = "SELECT id_user, nom, prenom, email, mdp, role, avatar, est_valide
        FROM utilisateur 
        WHERE email = :email 
        LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        redirectWith('error', "Identifiants ou mot de passe incorrects.", '../../view/connexion.php');
    }
    // Vérifier que le compte est validé
    if ((int)($user['est_valide'] ?? 0) !== 1) {
        redirectWith(
            'error',
            "Votre compte n'a pas encore été validé par un gestionnaire.",
            '../../view/connexion.php'
        );
    }
    if (!password_verify($motDePasse, $user['mdp'])) {
        redirectWith('error', "Identifiants ou mot de passe incorrects.", '../../view/connexion.php');
    }
    if (password_needs_rehash($user['mdp'], PASSWORD_DEFAULT)) {
        $newHash = password_hash($motDePasse, PASSWORD_DEFAULT);
        $upd = $pdo->prepare("UPDATE utilisateur SET mdp = :mdp WHERE id_user = :id");
        $upd->execute(['mdp' => $newHash, 'id' => $user['id_user']]);
    }
    $_SESSION['utilisateur'] = [
        'id_user' => (int)$user['id_user'],
        'nom'     => $user['nom'],
        'prenom'  => $user['prenom'],
        'email'   => $user['email'],
        'role'    => $user['role'],
        'avatar'  => $user['avatar'] ?? null,
    ];

    switch ($user['role']) {
          case 'Étudiant':
               $etudiantRepo = new etudiantRepository($pdo);
               $etudiant = $etudiantRepo->findByUserId($user['id_user']);
               $_SESSION['utilisateur'] = array_merge($_SESSION['utilisateur'], [
                    'annee_promo'   => $etudiant['annee_promo'] ?? null,
                    'ref_formation' => $etudiant['ref_formation'] ?? null,
                    'cv'            => $etudiant['cv'] ?? null,
               ]);
               break;

          case 'Alumni':
               $alumniRepo = new alumniRepository($pdo);
               $alumni = $alumniRepo->findByUserId($user['id_user']);
               $_SESSION['utilisateur'] = array_merge($_SESSION['utilisateur'], [
                    'annee_promo'         => $alumni['annee_promo'] ?? null,
                    'cv'                  => $alumni['cv'] ?? null,
                    'poste'               => $alumni['poste'] ?? null,
                    'ref_fiche_entreprise'=> $alumni['ref_fiche_entreprise'] ?? null,
               ]);
               break;

          case 'Professeur':
               $professeurRepo = new professeurRepository($pdo);
               $professeur = $professeurRepo->findByUserId($user['id_user']);
               $_SESSION['utilisateur'] = array_merge($_SESSION['utilisateur'], [
                    'specialite' => $professeur['specialite'] ?? null,
               ]);
               break;

          case 'Partenaire':
               $partenaireRepo = new partenaireRepository($pdo);
               $partenaire = $partenaireRepo->findByUserId($user['id_user']);
               $_SESSION['utilisateur'] = array_merge($_SESSION['utilisateur'], [
                    'poste'               => $partenaire['poste'] ?? null,
                    'cv'                  => $partenaire['cv'] ?? null,
                    'ref_fiche_entreprise'=> $partenaire['ref_fiche_entreprise'] ?? null,
               ]);
               break;
     }

     $_SESSION['success'] = "Bienvenue, {$user['prenom']} !";
    session_write_close();
    header("Location: ../../view/accueil.php");
    exit();

} catch (PDOException $e) {
    redirectWith('error', "Erreur de connexion : " . $e->getMessage(), '../../view/connexion.php');
}
