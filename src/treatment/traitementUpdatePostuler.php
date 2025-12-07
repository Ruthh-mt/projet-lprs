<?php
// -----------------------------------------------------
/* // DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// -----------------------------------------------------
*/
session_start();

//rediriger avec un message d'erreur
function redirectWithError($message) {
    $_SESSION['error_message'] = $message;
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

//vérifier si login ok
if (!isset($_SESSION['utilisateur']['id_user'])) {
    redirectWithError("Erreur : utilisateur non connecté.");
}

require_once __DIR__ . '/../../src/bdd/config.php';
require_once __DIR__ . '/../repository/PostulerRepository.php';

// Activer l'affichage des erreurs
header('Content-Type: text/html; charset=utf-8');

try {
    $postulerRepo = new PostulerRepository();

    // Vérification méthode
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Méthode non autorisée.");
    }

    // Vérification des champs
    if (!isset($_POST['ref_offre'])) {
        throw new Exception("Erreur : ref_offre manquant.");
    }

    if (!isset($_POST['motivation'])) {
        throw new Exception("Erreur : motivation manquante.");
    }

    // Récupération des valeurs
    $ref_user = (int) $_SESSION['utilisateur']['id_user'];
    $ref_offre = (int) $_POST['ref_offre'];
    $motivation = trim($_POST['motivation']);

    // Debug
    error_log("Tentative de mise à jour de la candidature :");
    error_log("- ref_user: " . $ref_user);
    error_log("- ref_offre: " . $ref_offre);
    error_log("- motivation: " . substr($motivation, 0, 100) . (strlen($motivation) > 100 ? '...' : ''));

    // Validation des données
    if ($ref_user <= 0 || $ref_offre <= 0) {
        throw new Exception("Référence utilisateur ou offre invalide.");
    }

    if (empty($motivation)) {
        throw new Exception("La lettre de motivation ne peut pas être vide.");
    }

    // UPDATE
    $maj = $postulerRepo->updateCandidature($ref_user, $ref_offre, $motivation);

    if ($maj) {
        $_SESSION['success_message'] = 'Votre candidature a été mise à jour avec succès.';
        header("Location: ../../view/profil.php?success=1");
        exit;
    } else {
        $error = $postulerRepo->getLastError();
        throw new Exception("Échec de la mise à jour de la candidature. " . $error);
    }

} catch (Exception $e) {
    // erreur
    $error_message = $e->getMessage();
    error_log("Erreur dans traitementUpdatePostuler.php : " . $error_message);
    
    //  pour le débogage
    echo "<h2>Erreur lors de la mise à jour</h2>";
    echo "<p>" . htmlspecialchars($error_message) . "</p>";
    echo "<p><a href='javascript:history.back()'>Retour au formulaire</a></p>";
    
 /*   // debug
    echo "<h3>Données reçues :</h3>";
    echo "<pre>";
    echo "POST : ";
    print_r($_POST);
    echo "\nSESSION : ";
    print_r($_SESSION['utilisateur'] ?? 'Session non disponible');
    echo "</pre>";
   */

}
?>
