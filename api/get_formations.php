<?php
require_once __DIR__ . '/../src/repository/FormationRepository.php';

header('Content-Type: application/json');

$formationRepository = new FormationRepository();
$formations = $formationRepository->findAll('nom');

echo json_encode($formations);
