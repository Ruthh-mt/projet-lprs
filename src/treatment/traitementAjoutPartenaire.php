<?php
// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'Gestionnaire') {
    $_SESSION['error'] = "Accès refusé. Vous devez être connecté en tant qu'administrateur.";
    header('Location: ../../connexion.php');
    exit();
}

// Inclure les fichiers nécessaires
require_once __DIR__ . '/../modele/ModelePartenaire.php';
require_once __DIR__ . '/../repository/PartenaireRepository.php';
require_once __DIR__ . '/../repository/UtilisateurRepository.php';

// Initialiser les variables
$errors = [];
$success = false;

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer l'ID de l'utilisateur à promouvoir (si fourni)
    $utilisateur_id = !empty($_POST['utilisateur_id']) ? (int)$_POST['utilisateur_id'] : null;

    // Si un utilisateur est sélectionné, on le promeut
    if ($utilisateur_id) {
        try {
            $utilisateurRepo = new UtilisateurRepository();
            $partenaireRepo = new PartenaireRepository();

            // Vérifier si l'utilisateur existe
            $utilisateur = $utilisateurRepo->getUserById($utilisateur_id);

            if (!$utilisateur) {
                $errors[] = "L'utilisateur sélectionné n'existe pas.";
            } else {
                // Mettre à jour le rôle de l'utilisateur
                $result = $utilisateurRepo->update($utilisateur_id, ['role' => 'Partenaire']);

                if ($result) {
                    $_SESSION['success'] = "L'utilisateur a été promu avec succès en tant que partenaire.";
                    header('Location: ../../view/crudPartenairee/partenaireRead.php');
                    exit();
                } else {
                    $errors[] = "Une erreur est survenue lors de la promotion de l'utilisateur.";
                }
            }
        } catch (Exception $e) {
            $errors[] = "Erreur technique : " . $e->getMessage();
        }
    } else {
        $prenom = trim($_POST['prenom'] ?? '');
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mdp = $_POST['mdp'] ?? '';
        $confirmation_mdp = $_POST['confirmation_mdp'] ?? '';

        // Validation des champs
        if (empty($prenom)) {
            $errors[] = "Le prénom est obligatoire.";
        }

        if (empty($nom)) {
            $errors[] = "Le nom est obligatoire.";
        }

        if (empty($email)) {
            $errors[] = "L'email est obligatoire.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Le format de l'email est invalide.";
        }

        if (empty($mdp)) {
            $errors[] = "Le mot de passe est obligatoire.";
        } elseif (strlen($mdp) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }

        if ($mdp !== $confirmation_mdp) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        // Validation des champs
        if (empty($prenom)) {
            $errors[] = "Le prénom est obligatoire.";
        }

        if (empty($nom)) {
            $errors[] = "Le nom est obligatoire.";
        }

        if (empty($email)) {
            $errors[] = "L'email est obligatoire.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Le format de l'email est invalide.";
        }

        if (empty($mdp)) {
            $errors[] = "Le mot de passe est obligatoire.";
        } elseif (strlen($mdp) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }

        if ($mdp !== $confirmation_mdp) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        // Si aucune erreur, procéder à la création du gestionnaire
        if (empty($errors)) {
            try {
                $utilisateurRepo = new UtilisateurRepository();
                $partenaireRepo = new PartenetaireRepository();

                // Vérifier si l'email existe déjà
                if ($utilisateurRepo->getUserByEmail($email)) {
                    $errors[] = "Un compte avec cet email existe déjà.";
                } else {
                    // Créer un nouvel utilisateur avec le rôle Gestionnaire
                    $partenaire = new ModelePartenaire([
                        'prenom' => $prenom,
                        'nom' => $nom,
                        'email' => $email,
                        'mdp' => $mdp,
                        'role' => 'Partenaire'
                    ]);

                    // Sauvegarder le gestionnaire dans la base de données
                    $result = $utilisateurRepo->inscription($partenaire);

                    if ($result) {
                        $success = true;
                        $_SESSION['success'] = "Le gestionnaire a été créé avec succès.";
                        header('Location: ../../view/crudPartenaire/partenaireRead.php');
                        exit();
                    } else {
                        $errors[] = "Une erreur est survenue lors de la création du partenaire.";
                    }
                }
            } catch (Exception $e) {
                $errors[] = "Erreur technique : " . $e->getMessage();
            }
        }
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data'] = [
            'prenom' => $prenom,
            'nom' => $nom,
            'email' => $email
        ];

        // Rediriger vers le formulaire avec les erreurs
        header('Location: ../../view/crudPartenaire/partenaireCreate.php');
        exit();
    }
} else {
    header('Location: ../../view/crudPartenaire/partenaireCreate.php');
    exit();
}
