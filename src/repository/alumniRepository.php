<?php
class alumniRepository
{
     private PDO $pdo;

     public function __construct(PDO $pdo)
     {
          $this->pdo = $pdo;
     }

     public function findByUserId(int $ref_user): ?array
     {
          $sql = "SELECT * FROM alumni WHERE ref_user = :ref_user";
          $stmt = $this->pdo->prepare($sql);
          $stmt->execute(['ref_user' => $ref_user]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          return $result ?: null;
     }

     public function insert(array $data): bool
     {
          $sql = "INSERT INTO alumni (ref_user, cv, annee_promo, poste, ref_fiche_entreprise) 
                VALUES (:ref_user, :cv, :annee_promo, :poste, :ref_fiche_entreprise)";
          $stmt = $this->pdo->prepare($sql);

          return $stmt->execute([
               'ref_user'            => $data['ref_user'],
               'cv'                  => $data['cv'] ?? null,
               'annee_promo'         => $data['annee_promo'],
               'poste'               => $data['poste'] ?? null,
               'ref_fiche_entreprise'=> $data['ref_fiche_entreprise'] ?? null,
          ]);
     }

     public function update(int $ref_user, array $data): bool
     {
          $sql = "UPDATE alumni 
                SET cv = :cv,
                    annee_promo = :annee_promo,
                    poste = :poste,
                    ref_fiche_entreprise = :ref_fiche_entreprise
                WHERE ref_user = :ref_user";
          $stmt = $this->pdo->prepare($sql);

          return $stmt->execute([
               'ref_user'            => $ref_user,
               'cv'                  => $data['cv'] ?? null,
               'annee_promo'         => $data['annee_promo'],
               'poste'               => $data['poste'] ?? null,
               'ref_fiche_entreprise'=> $data['ref_fiche_entreprise'] ?? null,
          ]);
     }

     public function delete(int $ref_user): bool
     {
          $sql = "DELETE FROM alumni WHERE ref_user = :ref_user";
          $stmt = $this->pdo->prepare($sql);

          return $stmt->execute(['ref_user' => $ref_user]);
     }
}
