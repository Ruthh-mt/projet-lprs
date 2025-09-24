<?php
require_once "../modele/userModel.php";

class UserRepository
{
    private $db;

    public function __construct()
    {
        $this->db = New Config();
    }

    public function inscription(userModel $user)
    {
        $sql = 'INSERT INTO utilisateur(nom,prenom,email,mdp,role) 
                Values (:nom,:prenom,:email,:mdp,:role)';
        $req = $this->db->connexion()->prepare($sql);
        $req->execute(array(
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'email' => $user->getEmail(),
            'mdp' => $user->getMdp(),
            'role'=>$user->getRole()
        ));
    }



    public function findById(UserModel $user)
    {
        $sql = "SELECT * FROM utilisateur WHERE id_user = :id_user";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['id_user' => $user->getIdUser()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToUser($row) : null;
    }

    public function findByEmail(UserModel $user)
    {
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['email' => $user->getEmail()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToUser($row) : null;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM utilisateur";
        $stmt = $this->db->connexion()->prepare($sql);

        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $this->mapToUser($row);
        }
        return $users;
    }

    public function save(UserModel $user): bool
    {
        if ($this->exists($user->getIdUser())) {
            $sql = "UPDATE utilisateur 
                    SET nom = :nom, prenom = :prenom, email = :email, mdp = :mdp
                    WHERE id_user = :id_user";
        } else {
            $sql = "INSERT INTO utilisateur (id_user, nom, prenom, email, mdp) 
                    VALUES (:id_user, :nom, :prenom, :email, :mdp)";
        }

        $stmt = $this->db->connexion()->prepare($sql);

        return $stmt->execute([
            'id_user' => $user->getIdUser(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'email' => $user->getEmail(),
            'mdp' => $user->getMdp(),
        ]);
    }

    public function delete(int $id_user): bool
    {
        $sql = "DELETE FROM utilisateur WHERE id_user = :id_user";
        $stmt = $this->db->connexion()->prepare($sql);
        return $stmt->execute(['id_user' => $id_user]);
    }

    private function exists(int $id_user): bool
    {
        $sql = "SELECT COUNT(*) FROM utilisateur WHERE id_user = :id_user";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['id_user' => $id_user]);
        return $stmt->fetchColumn() > 0;
    }

    private function mapToUser(array $row)
    {
        return new UserModel(
            $row['id_user'],
            $row['nom'],
            $row['prenom'],
            $row['email'],
            $row['mdp'],
            $row['role']
        );
    }

    public function changerMdp($mdp, $email)
    {
        $pdo = $this->db->connexion();
        $update = "UPDATE utilisateur SET mdp=:mdp WHERE email=:email";
        $req = $pdo->prepare($update);
        $req->execute(array(
            'mdp' => $mdp,
            'email' => $email
        ));
    }

    public function verifierToken($token)
    {
        $config = new Config();
        $pdo = $config->connexion();
        $verif = "SELECT email,mdp FROM utilisateur u inner join mdp_reset m on u.id_user = m.ref_user  WHERE token=:token";
        $req = $pdo->prepare($verif);
        $req->execute(array(
            'token' => $token
        ));
        return $req->fetch();
    }
}
