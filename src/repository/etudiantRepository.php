<?php
class etudiantRepository
{
     private $db;
     public function __construct()
     {
          $this->db=NEW Config();
     }

     public function findByUserId(int $ref_user): ?array
     {
          $sql = "SELECT * FROM etudiant WHERE ref_user = :ref_user";
          $stmt = $this->db->prepare($sql);
          $stmt->execute(['ref_user' => $ref_user]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          return $result ?: null;
     }

     public function insert(array $data): bool
     {
          $sql = "INSERT INTO etudiant (ref_user, cv, annee_promo, ref_formation) 
                VALUES (:ref_user, :cv, :annee_promo, :ref_formation)";
          $stmt = $this->db->prepare($sql);

          return $stmt->execute([
               'ref_user'      => $data['ref_user'],
               'cv'            => $data['cv'] ?? null,
               'annee_promo'   => $data['annee_promo'],
               'ref_formation' => $data['ref_formation'],
          ]);
     }

     public function update(int $ref_user, array $data): bool
     {
          $sql = "UPDATE etudiant 
                SET cv = :cv,
                    annee_promo = :annee_promo,
                    ref_formation = :ref_formation
                WHERE ref_user = :ref_user";
          $stmt = $this->db->prepare($sql);

          return $stmt->execute([
               'ref_user'      => $ref_user,
               'cv'            => $data['cv'] ?? null,
               'annee_promo'   => $data['annee_promo'],
               'ref_formation' => $data['ref_formation'],
          ]);
     }

     public function delete(int $ref_user): bool
     {
          $sql = "DELETE FROM etudiant WHERE ref_user = :ref_user";
          $stmt = $this->db->prepare($sql);

          return $stmt->execute(['ref_user' => $ref_user]);
     }
}
