<?php
// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../repository/FicheEntrepriseRepository.php';
require_once __DIR__ . '/../modele/ModeleFicheEntreprise.php';

// Définir l'en-tête de réponse en JSON
header('Content-Type: application/json');

// Vérifier si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée. Seules les requêtes POST sont acceptées.'
    ]);
    exit();
}

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et est un gestionnaire
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'Gestionnaire') {
    http_response_code(403); // Forbidden
    echo json_encode([
        'success' => false,
        'message' => 'Accès non autorisé. Vous devez être connecté en tant que gestionnaire.'
    ]);
    exit();
}

// Récupérer et valider l'ID de la fiche entreprise
if (!isset($_POST['id_fiche_entreprise']) || !is_numeric($_POST['id_fiche_entreprise'])) {
    http_response_code(400); // Bad Request
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

// Tableau pour stocker les erreurs de validation
$errors = [];

// Validation des données
if (empty($nom)) {
    $errors[] = 'Le nom de l\'entreprise est obligatoire.';
}

if (empty($adresse)) {
    $errors[] = 'L\'adresse est obligatoire.';
}

// Si des erreurs de validation, on les retourne
if (!empty($errors)) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'message' => 'Erreurs de validation',
        'errors' => $errors
    ]);
    exit();
}

try {
    $ficheEntrepriseRepository = new FicheEntrepriseRepository();
    
    // Préparer les données pour la mise à jour
    $data = [
        'nom' => $nom,
        'adresse' => $adresse,
        'code_postal' => $code_postal,
        'ville' => $ville,
        'pays' => $pays,
        'web' => $adresse_web
    ];
    
    // Mettre à jour la fiche entreprise dans la base de données
    try {
        $result = $ficheEntrepriseRepository->updateFiche($id_fiche_entreprise, $data);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'L\'entreprise a été mise à jour avec succès.',
                'redirect' => '../crudEntreprise/entrepriseRead.php'
            ]);
        } else {
            throw new Exception("La mise à jour n'a pas abouti. Aucune modification n'a été effectuée.");
        }
    } catch (PDOException $e) {
        throw new Exception("Erreur de base de données : " . $e->getMessage());
    }
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur : ' . $e->getMessage()
    ]);
}
