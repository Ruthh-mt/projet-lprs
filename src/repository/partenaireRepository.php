<?php
declare(strict_types=1);

class partenaireRepository
{
     private $db;
     public function __construct()
     {
          $this->db=NEW Config();
     }

     public function findByUserId(int $ref_user): ?array
     {
          $sql = "SELECT * FROM partenaire WHERE ref_user = :ref_user";
          $stmt = $this->db->connexion()->prepare($sql);
          $stmt->execute(['ref_user' => $ref_user]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          return $result ?: null;
     }

     public function insert(array $data): bool
     {
          $sql = "INSERT INTO partenaire (ref_user, cv, poste, ref_fiche_entreprise) 
                VALUES (:ref_user, :cv, :poste, :ref_fiche_entreprise)";
          $stmt = $this->db->connexion()->prepare($sql);

          return $stmt->execute([
               'ref_user'            => $data['ref_user'],
               'cv'                  => $data['cv'] ?? null,
               'poste'               => $data['poste'],
               'ref_fiche_entreprise'=> $data['ref_fiche_entreprise'] ?? null,
          ]);
     }

     public function update(int $ref_user, array $data): bool
     {
          $sql = "UPDATE partenaire 
                SET cv = :cv,
                    poste = :poste,
                    ref_fiche_entreprise = :ref_fiche_entreprise
                WHERE ref_user = :ref_user";
          $stmt = $this->db->connexion()->prepare($sql);

          return $stmt->execute([
               'ref_user'            => $ref_user,
               'cv'                  => $data['cv'] ?? null,
               'poste'               => $data['poste'],
               'ref_fiche_entreprise'=> $data['ref_fiche_entreprise'] ?? null,
          ]);
     }

     public function delete(int $ref_user): bool
     {
          $sql = "DELETE FROM partenaire WHERE ref_user = :ref_user";
          $stmt = $this->db->connexion()->prepare($sql);

          return $stmt->execute(['ref_user' => $ref_user]);
     }
     public function getAllOffresByPartenaire(int $ref_user){
         $sql = "SELECT * FROM offre WHERE ref_user = :ref_user";
         $stmt = $this->db->connexion()->prepare($sql);
         $stmt->execute(['ref_user' => $ref_user]);
         $result = $stmt->fetch(PDO::FETCH_ASSOC);

         return $result ?: null;
     }
}
