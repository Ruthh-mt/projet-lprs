<?php
class UserModel
{
     private $db;

     public function __construct(PDO $db)
     {
          $this->db = $db;
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
     public function createUser(array $data): bool
     {
          $sql = "INSERT INTO utilisateur (id_user, nom, prenom, email, mdp, est_valide) 
                VALUES (:id_user, :nom, :prenom, :email, :mdp, :est_valide)";

          $stmt = $this->db->prepare($sql);

          return $stmt->execute([
               'id_user'   => $data['id_user'],
               'nom'       => $data['nom'],
               'prenom'    => $data['prenom'],
               'email'     => $data['email'],
               'mdp'       => password_hash($data['mdp'], PASSWORD_BCRYPT),
               'est_valide'=> $data['est_valide'] ?? 0,
          ]);
     }

     public function updateUser(int $id_user, array $data): bool
     {
          $sql = "UPDATE utilisateur 
                SET nom = :nom, prenom = :prenom, email = :email, est_valide = :est_valide
                WHERE id_user = :id_user";

          $stmt = $this->db->prepare($sql);

          return $stmt->execute([
               'id_user'   => $id_user,
               'nom'       => $data['nom'],
               'prenom'    => $data['prenom'],
               'email'     => $data['email'],
               'est_valide'=> $data['est_valide'] ?? 0,
          ]);
     }

     public function deleteUser(int $id_user): bool
     {
          $sql = "DELETE FROM utilisateur WHERE id_user = :id_user";
          $stmt = $this->db->prepare($sql);
          return $stmt->execute(['id_user' => $id_user]);
     }

     public function getAllUsers(): array
     {
          $sql = "SELECT * FROM utilisateur";
          $stmt = $this->db->query($sql);
          return $stmt->fetchAll(PDO::FETCH_ASSOC);
     }
}
