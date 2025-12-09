<?php
require_once __DIR__ . '/../bdd/config.php';

class utilisateurRepository
{
    private PDO $db;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo instanceof PDO) {
            $this->db = $pdo;
        } else {
            $this->db = (new Config())->connexion();
        }
    }

    public function getUserById(int $id_user): ?array
    {
        $sql = "SELECT * FROM utilisateur WHERE id_user = :id_user";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_user' => $id_user]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function getUserByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM utilisateur";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function inscription(ModeleUtilisateur $data)
    {
        $sql = "INSERT INTO utilisateur (nom, prenom, email, mdp, role, ref_validateur, est_valide)
            VALUES (:nom, :prenom, :email, :mdp, :role, :ref_validateur, :est_valide)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nom'           => $data->getNom(),
            'prenom'        => $data->getPrenom(),
            'email'         => $data->getEmail(),
            'mdp'           => $data->getMdp(),
            'role'          => $data->getRole(),
            'ref_validateur'=> $data->getRefValidateur() ?? null,
            'est_valide'    => 0, // compte toujours non validé à la création
            'nom' => $data->getNom(),
            'prenom' => $data->getPrenom(),
            'email' => $data->getEmail(),
            'mdp' => $data->getMdp(),
            'role' => $data->getRole(),
            'ref_validateur' => $data->getRefValidateur() ?? null,
        ]);
    }

    public function update(int $id_user, array $data): bool
    {
        $allowed = [
            'nom', 'prenom', 'email', 'mdp', 'role', 'ref_validateur',
            'telephone', 'date_naissance', 'ville_residence', 'avatar',
            'est_valide',
        ];
        $set = [];
        $params = ['id_user' => $id_user];
        foreach ($allowed as $k) {
            if (array_key_exists($k, $data)) {
                $set[] = "$k = :$k";
                $params[$k] = $data[$k];
            }
        }
        if (!$set) return false;
        $sql = "UPDATE utilisateur SET " . implode(', ', $set) . " WHERE id_user = :id_user";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete(int $id_user): bool
    {
        $sql = "DELETE FROM utilisateur WHERE id_user = :id_user";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id_user' => $id_user]);
    }

    public function changerMdp($mdp, $email)
    {
    }

    public function findAllEtudiants(): array
    {
        $sql = "SELECT * FROM utilisateur WHERE role = 'Étudiant' ORDER BY nom, prenom";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findNonGestionnaires(): array
    {
        $sql = "SELECT * FROM utilisateur WHERE role != 'Gestionnaire' ORDER BY nom, prenom";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findNonPartenaires(): array
    {
        $sql = "SELECT * FROM utilisateur WHERE role != 'Partenaire' ORDER BY nom, prenom";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getAllUtilisateurs($limit,$perPage)
    {
        $sql = "SELECT * FROM utilisateur LIMIT :limit,:perPage";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'limit' => $limit,
            'perPage' => $perPage
        ]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);

    }
    public function countAllUtilisateurs(){
        $sql = "SELECT COUNT(id_user) FROM utilisateur";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function findNonValides(): array
    {
        $sql = "SELECT * FROM utilisateur WHERE est_valide = 0";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

}