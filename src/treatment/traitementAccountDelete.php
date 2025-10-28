<?php

require_once __DIR__ . '/../bdd/config.php';
require_once __DIR__ . '/../repository/utilisateurRepository.php';
session_start();

function redirect(string $url): void { header('Location: ' . $url, true, 302); exit; }
function flash(string $type, string $message): void { $_SESSION['flash'][] = ['type'=>$type, 'message'=>$message]; }
function requirePost(): void {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        flash('warning', "Méthode invalide."); redirect('../../view/account/accountDelete.php');
    }
}
function verifyCsrf(): void {
    $t = $_POST['csrf_token'] ?? ''; $e = $_SESSION['csrf_token'] ?? '';
    if (!$t || !$e || !hash_equals($e, $t)) {
        flash('danger', "Jeton CSRF invalide."); redirect('../../view/account/accountDelete.php');
    }
}

requirePost();
verifyCsrf();

$me = $_SESSION['utilisateur'] ?? null;
if (!$me) { flash('warning', "Vous devez être connecté."); redirect('../../view/connexion.php'); }

$userId = (int)($me['id'] ?? $me['id_user'] ?? $me['id_utilisateur'] ?? 0);
$email  = (string)($me['email'] ?? '');

$postedUserId = (int)($_POST['user_id'] ?? 0);
$ack          = ($_POST['ack'] ?? '') === '1';
$confirmEmail = trim((string)($_POST['confirm_email'] ?? ''));

if ($postedUserId !== $userId) {
    flash('danger', "Requête invalide (identifiant incohérent)."); redirect('../../view/account/accountDelete.php');
}
if (!$ack) {
    flash('warning', "Veuillez cocher la confirmation."); redirect('../../view/account/accountDelete.php');
}
if (!$email || !$confirmEmail || strcasecmp($confirmEmail, $email) !== 0) {
    flash('danger', "L'email de confirmation ne correspond pas."); redirect('../../view/account/accountDelete.php');
}

try {
    if (!class_exists('Config')) {
        throw new RuntimeException("Classe Config introuvable (src/bdd/config.php).");
    }
    $pdo = (new Config())->connexion();
    if (!$pdo instanceof PDO) {
        throw new RuntimeException("Connexion BDD invalide.");
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("DELETE FROM reponse WHERE ref_user = :id");
    $stmt->execute([':id' => $userId]);

    $stmt = $pdo->prepare("DELETE r FROM reponse r INNER JOIN post p ON p.id_post = r.ref_post WHERE p.ref_user = :id");
    $stmt->execute([':id' => $userId]);

    $pdo->prepare("DELETE FROM user_evenement WHERE ref_user = :id")->execute([':id'=>$userId]);
    $pdo->prepare("DELETE FROM postuler       WHERE ref_user = :id")->execute([':id'=>$userId]);

    $pdo->prepare("DELETE FROM professeur_formation WHERE ref_user = :id")->execute([':id'=>$userId]);
    $pdo->prepare("DELETE FROM professeur           WHERE ref_user = :id")->execute([':id'=>$userId]);

    $pdo->prepare("DELETE FROM etudiant    WHERE ref_user = :id")->execute([':id'=>$userId]);
    $pdo->prepare("DELETE FROM alumni      WHERE ref_user = :id")->execute([':id'=>$userId]);
    $pdo->prepare("DELETE FROM partenaire  WHERE ref_user = :id")->execute([':id'=>$userId]);
    $pdo->prepare("DELETE FROM gestionnaire WHERE ref_user = :id")->execute([':id'=>$userId]);

    $pdo->prepare("DELETE FROM mdp_reset WHERE ref_user = :id")->execute([':id'=>$userId]);

    $pdo->prepare("DELETE FROM post WHERE ref_user = :id")->execute([':id'=>$userId]);

    $repo = new utilisateurRepository($pdo);
    if (!$repo->delete($userId)) {
        throw new RuntimeException("La suppression de l'utilisateur a échoué.");
    }

    $pdo->commit();

    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time()-42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();

    session_start();
    flash('success', "Votre compte a été supprimé définitivement.");
    redirect('../../view/accueil.php');

} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('[AccountDelete] ' . $e->getMessage());
    flash('danger', "Une erreur est survenue pendant la suppression.");
    redirect('../../view/account/accountDelete.php');
}
