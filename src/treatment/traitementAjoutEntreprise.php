<?php
// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure les dépendances nécessaires
require_once __DIR__ . '/../modele/ModeleFicheEntreprise.php';
require_once __DIR__ . '/../repository/FicheEntrepriseRepository.php';
require_once __DIR__ . '/../bdd/config.php';

// Initialiser les variables de réponse
$response = [
    'success' => false,
    'message' => '',
    'redirect' => ''
];

try {
    // Vérifier que la requête est de type POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée');
    }

    // Validation des données
    $nom = trim($_POST['nom_entreprise'] ?? '');
    $adresse = trim($_POST['adresse_entreprise'] ?? '');
    $web = trim($_POST['adresse_web'] ?? '');
    
    // Validation des champs obligatoires
    if (empty($nom)) {
        throw new Exception('Le nom de l\'entreprise est obligatoire');
    }
    
    // Création de l'entreprise
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
    
    // Succès
    $response['success'] = true;
    $response['message'] = 'L\'entreprise a été créée avec succès !';
    $response['redirect'] = '..x/crudEntreprise/entrepriseRead.php';
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Envoyer la réponse en JSON
header('Content-Type: application/json');
echo json_encode($response);
