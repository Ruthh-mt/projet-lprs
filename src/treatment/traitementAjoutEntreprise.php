<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../modele/ModeleFicheEntreprise.php';
require_once __DIR__ . '/../repository/FicheEntrepriseRepository.php';
require_once __DIR__ . '/../bdd/config.php';

$response = [
    'success' => false,
    'message' => '',
    'redirect' => ''
];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée');
    }

    $nom = trim($_POST['nom_entreprise'] ?? '');
    $adresse = trim($_POST['adresse_entreprise'] ?? '');
    $web = trim($_POST['adresse_web'] ?? '');
    
    if (empty($nom)) {
        throw new Exception('Le nom de l\'entreprise est obligatoire');
    }
    
    $repo = new FicheEntrepriseRepository();
    $data = [
        'nom' => $nom,
        'adresse' => $adresse,
        'web' => $web
    ];
    
    $id = $repo->createFiche($data);
    
    if (!$id) {
        throw new Exception('Une erreur est survenue lors de la création de l\'entreprise');
    }
    
    $response['success'] = true;
    $response['message'] = 'L\'entreprise a été créée avec succès !';
    $response['redirect'] = '../crudEntreprise/entrepriseRead.php';
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Envoyer la réponse en JSON
header('Content-Type: application/json');
echo json_encode($response);
