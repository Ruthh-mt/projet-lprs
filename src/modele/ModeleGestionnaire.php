<?php

class ModeleGestionnaire {
    private $id_user;
    private $prenom;
    private $nom;
    private $email;
    private $mdp;
    private $role = 'Gestionnaire';
    private $ref_validateur = null;
    private $avatar = null;

    // Constructeur
    public function __construct($data = []) {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    // Hydratation des données
    public function hydrate($data) {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    // Getters
    public function getId() {
        return $this->id_user;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getMdp() {
        return $this->mdp;
    }

    public function getRole() {
        return $this->role;
    }

    public function getRefValidateur() {
        return $this->ref_validateur;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    // Setters
    public function setId($id) {
        $this->id_user = (int) $id;
        return $this;
    }

    public function setPrenom($prenom) {
        $this->prenom = htmlspecialchars($prenom);
        return $this;
    }

    public function setNom($nom) {
        $this->nom = htmlspecialchars($nom);
        return $this;
    }

    public function setEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        }
        return $this;
    }

    public function setMdp($mdp) {
        // Ne pas hacher à nouveau si le mot de passe est déjà haché
        if (!empty($mdp)) {
            if (password_get_info($mdp)['algo'] === 0) {
                $this->mdp = password_hash($mdp, PASSWORD_DEFAULT);
            } else {
                $this->mdp = $mdp;
            }
        }
        return $this;
    }

    public function setRole($role) {
        $this->role = 'Gestionnaire'; // Toujours forcer le rôle à Gestionnaire
        return $this;
    }

    public function setRefValidateur($ref_validateur) {
        $this->ref_validateur = $ref_validateur ? (int)$ref_validateur : null;
        return $this;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
        return $this;
    }

    // Méthode pour vérifier le mot de passe
    public function verifierMdp($mdp) {
        return password_verify($mdp, $this->mdp);
    }
    
    // Méthode pour définir l'ID utilisateur (alias de setId pour la compatibilité)
    public function setId_user($id) {
        return $this->setId($id);
    }
    
    // Méthode pour obtenir l'ID utilisateur (alias de getId pour la compatibilité)
    public function getId_user() {
        return $this->getId();
    }
    
    // Méthode de compatibilité pour l'affichage (le poste n'existe pas dans la table utilisateur)
    public function getPoste() {
        return 'Non spécifié';
    }
}
