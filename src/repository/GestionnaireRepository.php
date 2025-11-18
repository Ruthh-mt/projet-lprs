<?php

require_once __DIR__ . '/../modele/ModeleGestionnaire.php';
require_once __DIR__ . '/../bdd/config.php';

class GestionnaireRepository {
    private $pdo;

    public function __construct() {
        try {
            $config = new Config();
            $this->pdo = $config->connexion();
        } catch (PDOException $e) {
            throw new Exception('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }

    // Récupérer un gestionnaire par son ID
    public function findById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM utilisateur WHERE id_user = ? AND role = "Gestionnaire"');
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new ModeleGestionnaire($data) : null;
    }

    // Récupérer un gestionnaire par son email
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare('SELECT * FROM utilisateur WHERE email = ? AND role = "Gestionnaire"');
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new ModeleGestionnaire($data) : null;
    }

    // Récupérer tous les gestionnaires
    public function findAll() {
        $stmt = $this->pdo->query('SELECT * FROM utilisateur WHERE role = "Gestionnaire" ORDER BY nom, prenom');
        $gestionnaires = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $gestionnaires[] = new ModeleGestionnaire($data);
        }
        return $gestionnaires;
    }

    // Créer un nouveau gestionnaire
    public function create(ModeleGestionnaire $gestionnaire) {
        $stmt = $this->pdo->prepare('INSERT INTO utilisateur (prenom, nom, email, mdp, role) VALUES (?, ?, ?, ?, "Gestionnaire")');
        $success = $stmt->execute([
            $gestionnaire->getPrenom(),
            $gestionnaire->getNom(),
            $gestionnaire->getEmail(),
            $gestionnaire->getMdp()
        ]);
        
        if ($success) {
            $gestionnaire->setId($this->pdo->lastInsertId());
            return $gestionnaire;
        }
        
        return false;
    }


    // Rétrograder un gestionnaire en étudiant
    public function downgradeToEtudiant($id) {
        $stmt = $this->pdo->prepare('UPDATE utilisateur SET role = "Étudiant" WHERE id_user = ? AND role = "Gestionnaire"');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
    
    // Ancienne méthode delete conservée pour compatibilité (mais ne devrait plus être utilisée)
    public function delete($id) {
        return $this->downgradeToEtudiant($id);
    }

    // Compter le nombre total de gestionnaires
    public function count() {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM utilisateur WHERE role = "Gestionnaire"');
        return $stmt->fetchColumn();
    }

    // Vérifier si un email existe déjà (pour éviter les doublons)
    public function emailExists($email, $excludeId = null) {
        $sql = 'SELECT COUNT(*) FROM utilisateur WHERE email = ? AND role = "Gestionnaire"';
        $params = [$email];
        
        if ($excludeId !== null) {
            $sql .= ' AND id_user != ?';
            $params[] = $excludeId;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
}
