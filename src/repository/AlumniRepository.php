<?php
class alumniRepository
{
     private $db;
     public function __construct()
     {
          $this->db=NEW Config();
     }
     public function findByUserId(int $ref_user): ?array
     {
          $sql = "SELECT * FROM alumni WHERE ref_user = :ref_user";
          $stmt = $this->db->connexion()->prepare($sql);
          $stmt->execute(['ref_user' => $ref_user]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          return $result ?: null;
     }

     public function insert(array $data): bool
     {
          $sql = "INSERT INTO alumni (ref_user, cv, annee_promo, poste, ref_fiche_entreprise) 
                VALUES (:ref_user, :cv, :annee_promo, :poste, :ref_fiche_entreprise)";
          $stmt = $this->db->connexion()->prepare($sql);

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
          $stmt = $this->db->connexion()->prepare($sql);

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
          $stmt = $this->db->connexion()->prepare($sql);

          return $stmt->execute(['ref_user' => $ref_user]);
     }
    public function getFicheByAlumni(int $ref_user): ?array
    {
        $sql = "
        SELECT 
            a.ref_user,
            a.poste,
            a.ref_fiche_entreprise,
            f.id_fiche_entreprise,
            f.nom_entreprise,
            f.adresse_entreprise,
            f.adresse_web
        FROM alumni a
        LEFT JOIN fiche_entreprise f 
            ON a.ref_fiche_entreprise = f.id_fiche_entreprise
        WHERE a.ref_user = :ref_user
    ";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['ref_user' => $ref_user]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    public function affecterFicheAlumni(int $idUser, int $idFiche): bool
    {
        $pdo = $this->db->connexion();

        // Vérifier si le partenaire existe
        $checkSql = "SELECT ref_user FROM  alumni WHERE ref_user = :refUser";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([':refUser' => $idUser]);
        $exists = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($exists) {
            // Mise à jour si existe
            $sql = "UPDATE alumni SET ref_fiche_entreprise = :idFiche WHERE ref_user = :idUser";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([':idFiche' => $idFiche, ':idUser' => $idUser]);
        } else {
            // Insertion si existe pas
            $sql = "INSERT INTO alumni (ref_user, ref_fiche_entreprise) VALUES (:idUser, :idFiche)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([':idUser' => $idUser, ':idFiche' => $idFiche]);
        }
    }
}
