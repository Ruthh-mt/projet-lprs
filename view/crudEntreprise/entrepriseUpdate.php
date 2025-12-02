<?php
$prefix = '/programmes/projet-lprs/public';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$root = $_SERVER['DOCUMENT_ROOT'] . '/programmes/projet-lprs';
require_once $root . '/src/repository/FicheEntrepriseRepository.php';
require_once $root . '/src/modele/ModeleFicheEntreprise.php';
require_once __DIR__ . '../../../src/bdd/config.php';

$page = 'Modification d\'entreprise';
$error = '';
$success = '';

// Vérifier si l'ID de la fiche entreprise est fourni
if (!isset($_GET['id_fiche_entreprise']) || !is_numeric($_GET['id_fiche_entreprise'])) {
    $_SESSION['error'] = 'ID de fiche entreprise manquant ou invalide';
    header('Location: entrepriseRead.php');
    exit();
}

$id_fiche_entreprise = (int)$_GET['id_fiche_entreprise'];
// Alias si tu veux continuer à utiliser $id_entreprise ailleurs
$id_entreprise = $id_fiche_entreprise;

$ficheEntrepriseRepository = new FicheEntrepriseRepository();

// Récupérer la fiche entreprise par son ID
$fiche = $ficheEntrepriseRepository->getFicheById($id_fiche_entreprise);

if (!$fiche) {
    $_SESSION['error'] = 'Aucune fiche entreprise trouvée pour cet ID';
    header('Location: entrepriseRead.php');
    exit();
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modification d'entreprise • LPRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <style>
        .form-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .required:after {
            content: " *";
            color: red;
        }
        .suggestions {
            position: relative;
            width: 100%;
        }
        .suggestions-list {
            position: absolute;
            z-index: 1000;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            background: white;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            display: none;
        }
        .suggestion-item {
            padding: 0.5rem 1rem;
            cursor: pointer;
        }
        .suggestion-item:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 border-bottom bg-dark">
    <div class="col-2 ms-3 mb-2 mb-md-0 text-light">
        <a href="../accueil.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="https://i.pinimg.com/originals/a0/50/1e/a0501e0c5659dcfde397299e4234e75a.gif" class="mx-3" style="max-width: 48px;">
            <div class="fs-4 text-light text-uppercase">LPRS</div>
        </a>
    </div>
    <ul class="nav col mb-2 justify-content-center mb-md-0">
        <li class="nav-item"><a href="../accueil.php" class="btn btn-outline-light dropdown me-2">Accueil</a></li>
        <li class="nav-item"><a href="../evenements.php" class="btn btn-outline-light me-2">Évènements</a></li>
        <li class="nav-item"><a href="../annuaire.php" class="btn btn-outline-light me-2">Annuaire</a></li>
        <li class="nav-item"><a href="../listeEleves.php" class="btn btn-outline-light me-2">Liste des élèves</a></li>
        <li class="nav-item"><a href="../emplois.php" class="btn btn-outline-light me-2">Emplois</a></li>
        <li class="nav-item"><a href="../forum.php" class="btn btn-outline-light me-2">Forum</a></li>
        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'Gestionnaire'): ?>
            <li class="nav-item">
                <a href="../administration.php" class="btn btn-outline-warning active me-2">Administration</a>
            </li>
        <?php endif; ?>
    </ul>
    <div class="col-2 text-end me-3">
        <div class="dropdown">
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <?php $avatar = $_SESSION['utilisateur']['avatar'] ?? null; ?>
                <a href="#" class="d-inline-block text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <?php if ($avatar): ?>
                        <img src="<?= $prefix.htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil" class="rounded-circle" style="max-width: 48px;object-fit:cover;">
                    <?php else: ?>
                        <i class="bi bi-person-circle fs-3 text-light"></i>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li><a class="dropdown-item text-primary" href="../account/accountRead.php"><i class="bi bi-person"></i> Mon compte</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../../src/treatment/traitementUpdateEntreprise.php" method="POST"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
                </ul>
            <?php else: ?>
                <a href="#" class="d-inline-block text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-3 text-light"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li><a class="dropdown-item text-primary" href="../connexion.php"><i class="bi bi-box-arrow-in-right"></i> Connexion</a></li>
                    <li><a class="dropdown-item text-success" href="../inscription.php"><i class="bi bi-person-plus"></i> Inscription</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</header>
<nav class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom text-white bg-dark">
    <div class="nav col mb-2 justify-content-center mb-md-0">
        <div class="btn-group mx-1" role="group" aria-label="Basic example">
            <a href="../crudEntreprise/entrepriseRead.php" class="btn btn-outline-info active">Entreprise</a>
            <a href="../crudEvenement/evenementListe.php" class="btn btn-outline-info">Évènement</a>
            <a href="../crudFormation/formationRead.php" class="btn btn-outline-info">Formation</a>
            <a href="../crudGestionnaire/gestionnaireRead.php" class="btn btn-outline-info">Gestionnaire</a>
            <a href="../crudOffre/offreListe.php" class="btn btn-outline-info">Offre</a>
            <a href="../crudPartenaire/partenaireRead.php" class="btn btn-outline-info">Partenaire</a>
            <a href="../crudPost/postListe.php" class="btn btn-outline-info">Post</a>
            <a href="../crudReponse/reponseRead.php" class="btn btn-outline-info">Réponses</a>
            <a href="../crudUtilisateur/utilisateurRead.php" class="btn btn-outline-info">Utilisateur</a>
        </div>
    </div>
</nav>

<section class="container mt-4">
    <div class="form-container">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="entrepriseRead.php" class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Retour à la liste
                    </a>
                </div>
            </div>
        <?php else: ?>
            <h2 class="mb-4">Modifier l'entreprise</h2>
            
            <form id="entrepriseForm" class="needs-validation" method="POST" action="<?= $prefix ?>/src/treatment/traitementUpdateEntreprise.php" novalidate>
                <div id="formAlert" class="alert d-none" role="alert"></div>
                
                <div class="mb-3">
                    <label for="nom_entreprise" class="form-label required">Nom de l'entreprise</label>
                    <input type="text" class="form-control" id="nom_entreprise" name="nom_entreprise" 
                           value="<?= htmlspecialchars($fiche['nom_entreprise'] ?? '') ?>" required>
                    <input type="hidden" name="id_fiche_entreprise" value="<?= $id_fiche_entreprise ?>">

                </div>
                
                <div class="mb-3">
                    <label for="adresse_recherche" class="form-label">Rechercher une adresse</label>
                    <div class="suggestions">
                        <input type="text" class="form-control" id="adresse_recherche" 
                               placeholder="Commencez à taper une adresse..." 
                               value="" autocomplete="off">
                        <div id="suggestions" class="suggestions-list"></div>
                    </div>
                    <div class="form-text">Sélectionnez une adresse dans les suggestions</div>
                </div>
                
                <div class="mb-3">
                    <label for="adresse_entreprise" class="form-label">Adresse complète</label>
                    <textarea class="form-control" id="adresse_entreprise" name="adresse_entreprise" 
                              rows="3" required><?= htmlspecialchars($fiche['adresse_entreprise'] ?? '') ?></textarea>
                    <input type="hidden" id="code_postal" name="code_postal" 
                           value="<?= htmlspecialchars($fiche['code_postal'] ?? '') ?>">
                    <input type="hidden" id="ville" name="ville" 
                           value="<?= htmlspecialchars($fiche['adresse_entreprise'] ?? '') ?>">
                    <input type="hidden" id="pays" name="pays" 
                           value="<?= htmlspecialchars($fiche['pays'] ?? 'France') ?>">
                </div>
                
                <div class="mb-3">
                    <label for="adresse_web" class="form-label">Site web</label>
                    <div class="input-group">
                        <span class="input-group-text">https://</span>
                        <input type="text" class="form-control" id="adresse_web" name="adresse_web" 
                               placeholder="www.exemple.com" 
                               value="<?= htmlspecialchars(str_replace('https://', '', $fiche['adresse_web'] ?? '')) ?>">
                    </div>
                    <div class="form-text">Ne pas inclure "https://"</div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="entrepriseRead.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <button type="submit" class="btn btn-outline-primary" id="submitBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="spinner" role="status" aria-hidden="true"></span>
                        <span id="btnText"><i class="bi bi-save"></i></span>
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Gestion de l'autocomplétion d'adresse
    const searchInput = document.getElementById('adresse_recherche');
    const suggestionsDiv = document.getElementById('suggestions');
    const adresseComplete = document.getElementById('adresse_entreprise');
    const codePostalInput = document.getElementById('code_postal');
    const villeInput = document.getElementById('ville');
    
    let timeoutId;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        const query = this.value.trim();
        
        if (query.length < 3) {
            suggestionsDiv.style.display = 'none';
            return;
        }
        
        timeoutId = setTimeout(() => {
            fetch(`https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(query)}&limit=5`)
                .then(response => response.json())
                .then(data => {
                    if (data.features && data.features.length > 0) {
                        displaySuggestions(data.features);
                    } else {
                        suggestionsDiv.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la recherche d\'adresse:', error);
                    suggestionsDiv.style.display = 'none';
                });
        }, 300);
    });
    
    function displaySuggestions(features) {
        suggestionsDiv.innerHTML = '';
        
        features.forEach(feature => {
            const div = document.createElement('div');
            div.className = 'suggestion-item';
            div.textContent = feature.properties.label;
            
            div.addEventListener('click', () => {
                const props = feature.properties;
                searchInput.value = '';
                adresseComplete.value = props.label;
                codePostalInput.value = props.postcode || '';
                villeInput.value = props.city || '';
                suggestionsDiv.style.display = 'none';
            });
            
            suggestionsDiv.appendChild(div);
        });
        
        suggestionsDiv.style.display = 'block';
    }
    
    // Masquer les suggestions quand on clique en dehors
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.suggestions')) {
            suggestionsDiv.style.display = 'none';
        }
    });
    
    // Gestion de la soumission du formulaire
    document.getElementById('entrepriseForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Ajout automatique de https:// si non présent
        const webInput = document.getElementById('adresse_web');
        if (webInput.value && !webInput.value.match(/^https?:\/\//i)) {
            webInput.value = 'https://' + webInput.value;
        }
        
        // Validation du formulaire
        const form = this;
        if (!form.checkValidity()) {
            e.stopPropagation();
            form.classList.add('was-validated');
            return;
        }
        
        // Désactiver le bouton et afficher le spinner
        const submitBtn = document.getElementById('submitBtn');
        const spinner = document.getElementById('spinner');
        const btnText = document.getElementById('btnText');
        const formAlert = document.getElementById('formAlert');
        
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        btnText.textContent = 'Traitement...';
        formAlert.classList.add('d-none');
        
        // Récupérer les données du formulaire
        const formData = new FormData(form);
        
        // Afficher les données du formulaire dans la console pour le débogage
        console.log('Données du formulaire :');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
        
        // Envoyer les données via fetch
        fetch('../../src/treatment/traitementUpdateEntreprise.php', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                formAlert.textContent = data.message || 'Une erreur est survenue lors de la mise à jour.';
                formAlert.classList.remove('d-none', 'alert-success');
                formAlert.classList.add('alert-danger');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            formAlert.textContent = 'Une erreur est survenue lors de la communication avec le serveur.';
            formAlert.classList.remove('d-none', 'alert-success');
            formAlert.classList.add('alert-danger');
        })
        .finally(() => {
            // Réactiver le bouton et cacher le spinner
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
            btnText.innerHTML = '<i class="bi bi-save"></i> Enregistrer les modifications';
            
            // Faire défiler jusqu'au message d'erreur
            if (!data || !data.success) {
                formAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });
    
    // Gestion de la validation du formulaire
    document.getElementById('entrepriseForm').addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('was-validated');
            return false;
        }
    });
</script>
</body>
</html>