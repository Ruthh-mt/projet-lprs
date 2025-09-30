<?php
declare(strict_types=1);

class utilisateurRepository
{
     private $db;

     public function __construct()
     {
          $this->db=NEW Config();
     }
     public function findById(int $id_user): ?array
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
          $stmt = $this->db->connexion()->prepare($sql);
          $stmt->execute(['email' => $email]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          return $result ?: null;
     }
     public function findAll(): array
     {
          $sql = "SELECT * FROM utilisateur";
          $stmt = $this->db->query($sql);

          return $stmt->fetchAll(PDO::FETCH_ASSOC);
     }
     public function inscription(UserModel $data)
     {
          $sql = "INSERT INTO utilisateur (nom, prenom, email, mdp, role, ref_validateur)
                VALUES (:nom, :prenom, :email, :mdp, :role, :ref_validateur)";
          $stmt = $this->db->connexion()->prepare($sql);

          return $stmt->execute([
               'nom'           => $data-> getNom(),
               'prenom'        => $data-> getPrenom(),
               'email'         => $data-> getEmail(),
               'mdp'           => $data-> getMdp(),
               'role'          => $data-> getRole(),
               'ref_validateur'=> $data-> getRefValidateur() ?? null,
          ]);
     }
     public function update(int $id_user, array $data): bool
     {
          $sql = "UPDATE utilisateur 
                SET nom = :nom,
                    prenom = :prenom,
                    email = :email,
                    mdp = :mdp,
                    role = :role,
                    ref_validateur = :ref_validateur
                WHERE id_user = :id_user";
          $stmt = $this->db->connexion()->prepare($sql);

          return $stmt->execute([
               'id_user'       => $id_user,
               'nom'           => $data['nom'],
               'prenom'        => $data['prenom'],
               'email'         => $data['email'],
               'mdp'           => $data['mdp'],
               'role'          => $data['role'],
               'ref_validateur'=> $data['ref_validateur'] ?? null,
          ]);
     }
     public function delete(int $id_user): bool
     {
          $sql = "DELETE FROM utilisateur WHERE id_user = :id_user";
          $stmt = $this->db->connexion()->prepare($sql);

          return $stmt->execute(['id_user' => $id_user]);
     }
}
