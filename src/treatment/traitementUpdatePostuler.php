<?php
session_start();

// Redirection avec message d'erreur
function redirectWithError(string $message): void {
    $_SESSION['error_message'] = $message;
    if (!empty($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header('Location: ../../view/emplois.php');
    }
    exit;
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur']['id_user'])) {
    redirectWithError("Erreur : utilisateur non connecté.");
}


require_once __DIR__ . '/../bdd/config.php';
require_once __DIR__ . '/../modele/ModelePostuler.php';
require_once __DIR__ . '/../repository/PostulerRepository.php';

try {
    // Vérification méthode
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Méthode non autorisée.");
    }

    $postulerRepo = new PostulerRepository();

    // Champs obligatoires
    if (!isset($_POST['ref_offre'])) {
        throw new Exception("Erreur : ref_offre manquant.");
    }

    $ref_user  = (int) $_SESSION['utilisateur']['id_user'];
    $ref_offre = (int) $_POST['ref_offre'];

    if ($ref_user <= 0 || $ref_offre <= 0) {
        throw new Exception("Référence utilisateur ou offre invalide.");
    }

    //  SUPPRESSION de la candidature
    if (isset($_POST['delete_candidature'])) {

        $ok = $postulerRepo->deleteCandidature($ref_user, $ref_offre);
        if (!$ok) {
            throw new Exception("Erreur lors de la suppression de la candidature.");
        }

        $_SESSION['success_message'] = "Votre candidature a été supprimée.";
        header("Location: ../../view/emplois.php?deleted=1");
        exit;
    }

    // AJOUT / MISE À JOUR de la candidature
    if (!isset($_POST['motivation'])) {
        throw new Exception("Erreur : motivation manquante.");
    }

    $motivation = trim($_POST['motivation']);

    if ($motivation === '') {
        throw new Exception("La lettre de motivation ne peut pas être vide.");
    }

    //CV UPLOAD
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {

        // Dossier des CV
        $cvDossier = __DIR__ . "/telechargement/candidatures/";
        if (!is_dir($cvDossier)) {
            mkdir($cvDossier, 0777, true);
        }

        $nomUser    = strtolower($_SESSION['utilisateur']['nom']);
        $prenomUser = strtolower($_SESSION['utilisateur']['prenom']);

        $tmp       = $_FILES['cv']['tmp_name'];
        $extension = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, ['pdf', 'doc', 'docx'])) {
            throw new Exception("Format de CV non autorisé (pdf, doc, docx uniquement).");
        }

        // Supprimer l'ancien CV s'il existe
        $modeleFichier = $cvDossier . "cv_" . $nomUser . "_" . $prenomUser . ".*";
        foreach (glob($modeleFichier) as $old) {
            @unlink($old);
        }

        // Nouveau nom
        $newName     = "cv_{$nomUser}_{$prenomUser}." . $extension;
        $destination = $cvDossier . $newName;

        if (!move_uploaded_file($tmp, $destination)) {
            throw new Exception("Erreur lors de l'enregistrement du CV.");
        }
    }

    // Vérifier si la candidature existe déjà
    $existe= $postulerRepo->findOffreAndUser($ref_user, $ref_offre);

    if (!$existe) {
        // INSERT
        $modele = new ModelePostuler([
            'ref_user'    => $ref_user,
            'ref_offre'   => $ref_offre,
            'motivation'  => $motivation
        ]);

        $ok = $postulerRepo->insert($modele);

        if (!$ok) {
            if (method_exists($postulerRepo, 'getLastError')) {
                $err = $postulerRepo->getLastError();
                throw new Exception("Impossible de créer la candidature. " . $err);
            } else {
                throw new Exception("Impossible de créer la candidature.");
            }
        }

        $_SESSION['success_message'] = 'Votre candidature a été enregistrée avec succès.';

    } else {
        // UPDATE
        $ok = $postulerRepo->updateCandidature($ref_user, $ref_offre, $motivation);

        if (!$ok) {
            if (method_exists($postulerRepo, 'getLastError')) {
                $err = $postulerRepo->getLastError();
                throw new Exception("Échec de la mise à jour de la candidature. " . $err);
            } else {
                throw new Exception("Échec de la mise à jour de la candidature.");
            }
        }

        $_SESSION['success_message'] = 'Votre candidature a été mise à jour avec succès.';
    }

    // Redirection vers la page d’affichage de la candidature
    header("Location: ../../view/crudPostuler/afficheCandidatures.php?id=". $ref_offre . "&success=1");
    exit;

} catch (Exception $e) {

    $error_message = $e->getMessage();
    error_log("Erreur dans traitementUpdatePostuler.php : " . $error_message);
}