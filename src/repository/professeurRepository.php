<?php
class professeurRepository
{
     private $db;
     public function __construct()
     {
          $this->db=NEW Config();
     }

     public function findByUserId(int $ref_user): ?array
     {
          $sql = "SELECT * FROM professeur WHERE ref_user = :ref_user";
          $stmt = $this->db->prepare($sql);
          $stmt->execute(['ref_user' => $ref_user]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          return $result ?: null;
     }

     public function insert(array $data): bool
     {
          $sql = "INSERT INTO professeur (ref_user, specialite) 
                VALUES (:ref_user, :specialite)";
          $stmt = $this->db->prepare($sql);

          return $stmt->execute([
               'ref_user'   => $data['ref_user'],
               'specialite' => $data['specialite'],
          ]);
     }

     public function update(int $ref_user, array $data): bool
     {
          $sql = "UPDATE professeur 
                SET specialite = :specialite
                WHERE ref_user = :ref_user";
          $stmt = $this->db->prepare($sql);

          return $stmt->execute([
               'ref_user'   => $ref_user,
               'specialite' => $data['specialite'],
          ]);
     }

     public function delete(int $ref_user): bool
     {
          $sql = "DELETE FROM professeur WHERE ref_user = :ref_user";
          $stmt = $this->db->prepare($sql);

          return $stmt->execute(['ref_user' => $ref_user]);
     }
}
