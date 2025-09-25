<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

require_once('../bdd/config.php');
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

    $sql = "SELECT id_user, nom, prenom, email, mdp, role 
            FROM utilisateur 
            WHERE email = :email 
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        redirectWith('error', "Identifiants incorrects.", '../../view/connexion.php');
    }
    if (!password_verify($motDePasse, $user['mdp'])) {
        redirectWith('error', "Identifiants incorrects.", '../../view/connexion.php');
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
    ];
    $_SESSION['success'] = "Bienvenue, {$user['prenom']} !";
    session_write_close();
    header("Location: ../../view/accueil.php");
    exit();

} catch (PDOException $e) {
    redirectWith('error', "Erreur de connexion : " . $e->getMessage(), '../../view/connexion.php');
}
