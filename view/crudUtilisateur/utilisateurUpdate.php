<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et est un gestionnaire
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'Gestionnaire') {
    $_SESSION['error'] = "Accès refusé. Vous devez être connecté en tant que gestionnaire.";
    header('Location: ../../connexion.php');
    exit();
}

// Inclure les fichiers nécessaires
require_once __DIR__ . '/../../src/modele/ModeleUtilisateur.php';
require_once __DIR__ . '/../../src/repository/UtilisateurRepository.php';

$page = 'Utilisateur';
$utilisateur = null;
$errors = [];

// Récupérer l'ID de l'utilisateur à modifier
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID d'utilisateur invalide.";
    header('Location: utilisateurRead.php');
    exit();
}

$id = (int)$_GET['id'];
$utilisateurRepo = new UtilisateurRepository();

// Récupérer les informations de l'utilisateur
$utilisateurData = $utilisateurRepo->getUserById($id);

if (!$utilisateurData) {
    $_SESSION['error'] = "Utilisateur introuvable.";
    header('Location: utilisateurRead.php');
    exit();
}

// Créer un objet ModeleUtilisateur à partir des données
require_once __DIR__ . '/../../src/modele/ModeleUtilisateur.php';

// Préparer les données pour l'hydratation
$donneesUtilisateur = [
    'idUser' => $utilisateurData['id_user'],
    'nom' => $utilisateurData['nom'],
    'prenom' => $utilisateurData['prenom'],
    'email' => $utilisateurData['email'],
    'role' => $utilisateurData['role'],
    'promotion' => $utilisateurData['promotion'] ?? null,
    'matiere' => $utilisateurData['matiere'] ?? null
];

// Créer l'instance avec les données
$utilisateur = new ModeleUtilisateur($donneesUtilisateur);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation et traitement des données
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? '');
    
    // Validation des champs obligatoires
    if (empty($prenom) || empty($nom) || empty($email) || empty($role)) {
        $errors[] = "Tous les champs obligatoires doivent être remplis.";
    }
    
    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email n'est pas valide.";
    }
    
    // Si pas d'erreurs, mettre à jour l'utilisateur
    if (empty($errors)) {
        $data = [
            'prenom' => $prenom,
            'nom' => $nom,
            'email' => $email,
            'role' => $role
        ];
        
        // Ajouter des champs spécifiques au rôle
        if ($role === 'Étudiant') {
            $data['promotion'] = $_POST['promotion'] ?? '';
        } elseif ($role === 'Enseignant') {
            $data['matiere'] = $_POST['matiere'] ?? '';
        }
        
        // Mettre à jour l'utilisateur
        if ($utilisateurRepo->update($id, $data)) {
            $_SESSION['success'] = "L'utilisateur a été mis à jour avec succès.";
            header('Location: utilisateurRead.php');
            exit();
        } else {
            $errors[] = "Une erreur est survenue lors de la mise à jour de l'utilisateur.";
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier un utilisateur • LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
</head>
<body>
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://media.tenor.com/ifEkV-aGn3EAAAAi/fat-cat.gif"
                 class="rounded-circle mx-3"
                 style="max-width: 15%; height: auto;">
            <div class="fs-4 text-light text-uppercase">LPRS • ADMIN</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="../../accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="../../evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="../../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="../../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="../../emplois.php" class="btn btn-outline-light me-2">Emplois</a></li>
        <li class="nav-item"><a href="../../forum.php" class="btn btn-outline-light me-2">Forum</a></li>
        <li class="nav-item">
            <a href="../../administration.php" class="btn btn-outline-warning active me-2">Administration</a>
        </li>
    </ul>
    <div class="col-2 btn-group md-3 me-3 text-end" role="group" aria-label="Boutons utilisateur">
        <a href="../../account/accountRead.php" class="btn btn-outline-primary">Mon compte</a>
        <a href="../../../src/treatment/traitementDeconnexion.php" class="btn btn-outline-danger">Déconnexion</a>
    </div>
</header>

<nav class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom text-white bg-dark">
    <div class="nav col mb-2 justify-content-center mb-md-0">
        <div class="btn-group mx-1" role="group" aria-label="Basic example">
            <a href="../crudEntreprise/entrepriseRead.php" class="btn btn-outline-info">Entreprise</a>
            <a href="../crudEvenement/evenementRead.php" class="btn btn-outline-danger disabled">Évènement</a>
            <a href="../crudFormation/formationRead.php" class="btn btn-outline-info">Formation</a>
            <a href="../crudGestionnaire/gestionnaireRead.php" class="btn btn-outline-info">Gestionnaire</a>
            <a href="../crudOffre/offreRead.php" class="btn btn-outline-info">Offre</a>
            <a href="../crudPartenaire/partenaireRead.php" class="btn btn-outline-info">Partenaire</a>
            <a href="../crudPost/postRead.php" class="btn btn-outline-danger">Post</a>
            <a href="../crudReponse/reponseRead.php" class="btn btn-outline-info">Réponses</a>
            <a href="../crudUtilisateur/utilisateurRead.php" class="btn btn-outline-info">Utilisateur</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Modifier un utilisateur</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" action="">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prénom *</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" 
                                       value="<?= htmlspecialchars($utilisateur->getPrenom()) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom *</label>
                                <input type="text" class="form-control" id="nom" name="nom" 
                                       value="<?= htmlspecialchars($utilisateur->getNom()) ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($utilisateur->getEmail()) ?>" required>
                        </div>
                        
                        <!-- Champ caché pour l'ID de l'utilisateur -->
                        <input type="hidden" name="id_user" value="<?= $utilisateur->getIdUser() ?>">
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle *</label>
                            <select class="form-select" id="role" name="role" required onchange="toggleFields()">
                                <option value="" selected disabled>-- Sélectionnez un rôle --</option>
                                <option value="Étudiant" <?= $utilisateur->getRole() === 'Étudiant' ? 'selected' : '' ?>>Étudiant</option>
                                <option value="Alumni" <?= $utilisateur->getRole() === 'Alumni' ? 'selected' : '' ?>>Alumni/Ancien élève</option>
                                <option value="Professeur" <?= $utilisateur->getRole() === 'Professeur' ? 'selected' : '' ?>>Professeur</option>
                                <option value="Partenaire" <?= $utilisateur->getRole() === 'Partenaire' ? 'selected' : '' ?>>Partenaire</option>
                                <option value="Gestionnaire" <?= $utilisateur->getRole() === 'Gestionnaire' ? 'selected' : '' ?>>Gestionnaire</option>
                            </select>
                        </div>
                        <!-- Champs spécifiques aux étudiants -->
                        <div id="etudiantFields" class="role-fields" style="display: <?= in_array($utilisateur->getRole(), ['Étudiant', 'Alumni']) ? 'block' : 'none' ?>;">
                            <div class="mb-3">
                                <label for="promotion" class="form-label">Promotion</label>
                                <input type="text" class="form-control" id="promotion" name="promotion"
                                       value="<?= htmlspecialchars($utilisateur->getPromotion() ?? '') ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="formation" class="form-label">Formation</label>
                                <select class="form-select" id="formation" name="formation">
                                    <option value="" <?= empty($utilisateur->getFormation()) ? 'selected' : '' ?>>-- Sélectionnez une formation --</option>
                                    <?php
                                    require_once __DIR__ . '/../../../src/repository/FormationRepository.php';
                                    $formationRepository = new FormationRepository();
                                    $formations = $formationRepository->findAll('nom');
                                    
                                    foreach ($formations as $formation) {
                                        $selected = ($formation->nom === $utilisateur->getFormation()) ? 'selected' : '';
                                        echo sprintf(
                                            '<option value="%s" %s>%s</option>',
                                            htmlspecialchars($formation->nom),
                                            $selected,
                                            htmlspecialchars($formation->nom)
                                        );
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Champs spécifiques aux alumni -->
                        <div id="alumniFields" class="role-fields" style="display: <?= $utilisateur->getRole() === 'Alumni' ? 'block' : 'none' ?>;">
                            <div class="mb-3">
                                <label for="poste" class="form-label">Poste actuel</label>
                                <input type="text" class="form-control" id="poste_alumni" name="poste"
                                       value="<?= htmlspecialchars($utilisateur->getPoste() ?? '') ?>">
                            </div>
                        </div>
                        
                        <!-- Champs spécifiques aux professeurs -->
                        <div id="professeurFields" class="role-fields" style="display: <?= $utilisateur->getRole() === 'Professeur' ? 'block' : 'none' ?>;">
                            <div class="mb-3">
                                <label for="matiere" class="form-label">Matière enseignée</label>
                                <input type="text" class="form-control" id="matiere" name="matiere"
                                       value="<?= htmlspecialchars($utilisateur->getMatiere() ?? '') ?>">
                            </div>
                        </div>
                        
                        <!-- Champs spécifiques aux entreprises/partenaires -->
                        <div id="partenaireFields" class="role-fields" style="display: <?= $utilisateur->getRole() === 'Partenaire' ? 'block' : 'none' ?>;">
                            <div class="mb-3">
                                <label for="poste" class="form-label">Poste occupé</label>
                                <input type="text" class="form-control" id="poste" name="poste"
                                       value="<?= htmlspecialchars($utilisateur->getPoste() ?? '') ?>">
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="utilisateurRead.php" class="btn btn-secondary me-md-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fonction pour afficher/masquer les champs en fonction du rôle sélectionné
    function toggleFields() {
        const role = document.getElementById('role').value;
        const fields = document.querySelectorAll('.role-fields');
        
        // Masquer tous les champs spécifiques
        fields.forEach(field => {
            field.style.display = 'none';
        });
        
        // Afficher les champs en fonction du rôle sélectionné
        switch(role) {
            case 'Étudiant':
                document.getElementById('etudiantFields').style.display = 'block';
                document.getElementById('alumniFields').style.display = 'none';
                break;
            case 'Alumni':
                document.getElementById('etudiantFields').style.display = 'block';
                document.getElementById('alumniFields').style.display = 'block';
                break;
            case 'Professeur':
                document.getElementById('professeurFields').style.display = 'block';
                break;
            case 'Partenaire':
                document.getElementById('partenaireFields').style.display = 'block';
                break;
        }
    }
    
    // Initialiser l'affichage des champs au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        toggleFields();
        
        // Mettre à jour l'affichage quand le rôle change
        const roleSelect = document.getElementById('role');
        if (roleSelect) {
            roleSelect.addEventListener('change', toggleFields);
        }
        toggleFields();
    });
</script>

<!-- Scripts Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
</body>
</html>