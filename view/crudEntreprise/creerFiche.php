<?php
session_start();
require_once('../../src/bdd/config.php');
require_once('../../src/repository/FicheEntrepriseRepository.php');
require_once('../../src/repository/PartenaireRepository.php');
require_once('../../src/repository/AlumniRepository.php');

if (!isset($_SESSION['utilisateur'])){
    header('Location: ../connexion.php');
    exit();
}
$ficheRepo = new FicheEntrepriseRepository();
$partenaireRepo = new PartenaireRepository();
$alumniRepo = new AlumniRepository();
$idUser = $_SESSION['utilisateur']['id_user'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom_entreprise']);
    $adresse = trim($_POST['adresse_entreprise']);
    $web = trim($_POST['adresse_web']);

    if (empty($nom) || empty($adresse) || empty($web)) {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
        header('Location: creerFiche.php');
        exit();
    }
    $check = $ficheRepo->findFicheByWeb($web);

    if ($check) {
        $_SESSION['error'] = "Une entreprise avec cette adresse web existe déjà.";
        header('Location: creerFiche.php');
        exit();
    }
    $idFicheCree = $ficheRepo->createFiche([
        'nom' => $nom,
        'adresse' => $adresse,
        'web' => $web
    ]);

    if ($idFicheCree && $_SESSION['utilisateur']['role'] == 'Partenaire') {
        $partenaireRepo->affecterFichePartenaire($idUser, $idFicheCree);
        $_SESSION['success'] = "Fiche entreprise créée et rattachée avec succès !";
        header("Location: ../emplois.php");
        exit();
    } elseif ($idFicheCree && $_SESSION['utilisateur']['role'] == 'Alumni') {
        $alumniRepo ->affecterFicheAlumni($idUser, $idFicheCree);
        $_SESSION['success'] = "Fiche entreprise créée et rattachée avec succès !";
        header("Location: ../emplois.php");
        exit();
    }
    else{

        $_SESSION['error'] = "Erreur lors de la création de la fiche entreprise.";
        header('Location:creerFiche.php');
        exit();
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une fiche entreprise • LPRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<header class="d-flex align-items-center justify-content-between p-3 border-bottom border-secondary">
    <a href="../emplois.php" class="btn btn-outline-light">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
    <h3 class="text-uppercase">Créer une fiche entreprise</h3>
    <div></div>
</header>

<main class="container py-5" style="max-width:600px;">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="card bg-secondary text-light shadow-lg p-4 rounded">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nom de l'entreprise</label>
                <input type="text" name="nom_entreprise" class="form-control" placeholder="Ex : Orange" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Adresse</label>
                <input type="text" name="adresse_entreprise" class="form-control" placeholder="Ex : 78 Rue Olivier de Serres, Paris" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Site web</label>
                <input type="url" name="adresse_web" class="form-control" placeholder="https://www.orange.fr" required>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <a href="../emplois.php" class="btn btn-outline-light me-2">Annuler</a>
                <button type="submit" class="btn btn-success">Créer la fiche</button>
            </div>
        </form>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
