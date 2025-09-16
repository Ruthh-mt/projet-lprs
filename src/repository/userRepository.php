<?php
require_once __DIR__ . '/User.php';

class UserRepository
{
     private $db;

     public function __construct(PDO $db)
     {
          $this->db = $db;
     }

     public function findById(int $id_user): ?User
     {
          $sql = "SELECT * FROM utilisateur WHERE id_user = :id_user";
          $stmt = $this->db->prepare($sql);
          $stmt->execute(['id_user' => $id_user]);
          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          return $row ? $this->mapToUser($row) : null;
     }

     public function findByEmail(string $email): ?User
     {
          $sql = "SELECT * FROM utilisateur WHERE email = :email";
          $stmt = $this->db->prepare($sql);
          $stmt->execute(['email' => $email]);
          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          return $row ? $this->mapToUser($row) : null;
     }

     public function findAll(): array
     {
          $sql = "SELECT * FROM utilisateur";
          $stmt = $this->db->query($sql);

          $users = [];
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
               $users[] = $this->mapToUser($row);
          }
          return $users;
     }
     public function save(User $user): bool
     {
          if ($this->exists($user->getId())) {
               $sql = "UPDATE utilisateur 
                    SET nom = :nom, prenom = :prenom, email = :email, mdp = :mdp, est_valide = :est_valide
                    WHERE id_user = :id_user";
          } else {
               $sql = "INSERT INTO utilisateur (id_user, nom, prenom, email, mdp, est_valide) 
                    VALUES (:id_user, :nom, :prenom, :email, :mdp, :est_valide)";
          }

          $stmt = $this->db->prepare($sql);

          return $stmt->execute([
               'id_user'   => $user->getId(),
               'nom'       => $user->getNom(),
               'prenom'    => $user->getPrenom(),
               'email'     => $user->getEmail(),
               'mdp'       => $user->getMdp(),
               'est_valide'=> $user->getEstValide(),
          ]);
     }
     public function delete(int $id_user): bool
     {
          $sql = "DELETE FROM utilisateur WHERE id_user = :id_user";
          $stmt = $this->db->prepare($sql);
          return $stmt->execute(['id_user' => $id_user]);
     }

     private function exists(int $id_user): bool
     {
          $sql = "SELECT COUNT(*) FROM utilisateur WHERE id_user = :id_user";
          $stmt = $this->db->prepare($sql);
          $stmt->execute(['id_user' => $id_user]);
          return $stmt->fetchColumn() > 0;
     }
     private function mapToUser(array $row): User
     {
          return new User(
               $row['id_user'],
               $row['nom'],
               $row['prenom'],
               $row['email'],
               $row['mdp'],
               $row['est_valide']
          );
     }
}
