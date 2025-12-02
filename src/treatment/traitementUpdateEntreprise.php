<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../repository/FicheEntrepriseRepository.php';
require_once __DIR__ . '/../modele/ModeleFicheEntreprise.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée. Seules les requêtes POST sont acceptées.'
    ]);
    exit();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'Gestionnaire') {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Accès non autorisé. Vous devez être connecté en tant que gestionnaire.'
    ]);
    exit();
}

if (!isset($_POST['id_fiche_entreprise']) || !is_numeric($_POST['id_fiche_entreprise'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'ID de fiche entreprise manquant ou invalide.'
    ]);
    exit();
}

$id_fiche_entreprise = (int)$_POST['id_fiche_entreprise'];

// Récupérer les données du formulaire
$nom = trim($_POST['nom_entreprise'] ?? '');
$adresse = trim($_POST['adresse_entreprise'] ?? '');
$code_postal = trim($_POST['code_postal'] ?? '');
$ville = trim($_POST['ville'] ?? '');
$pays = trim($_POST['pays'] ?? 'France');
$adresse_web = trim($_POST['adresse_web'] ?? '');

if (empty($nom) || empty($adresse) || empty($adresse_web)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Tous les champs sont obligatoires.'
    ]);
    exit();
}

try {
    $ficheRepo = new FicheEntrepriseRepository();
    
    $fiche = $ficheRepo->getFicheEntrepriseById($id_fiche_entreprise);
    
    if (!$fiche) {
        throw new Exception("La fiche entreprise demandée n'existe pas.");
    }
    
    $data = [
        'nom' => $nom,
        'adresse' => $adresse,
        'web' => $adresse_web
    ];
    
    $success = $ficheRepo->updateFiche($id_fiche_entreprise, $data);
    
    if (!$success) {
        throw new Exception("Une erreur est survenue lors de la mise à jour de la fiche entreprise.");
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'La fiche entreprise a été mise à jour avec succès.',
        'redirect' => '../../view/crudEntreprise/entrepriseRead.php'
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la mise à jour de la fiche entreprise : ' . $e->getMessage()
    ]);
}
