<?php

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
    public function findAll() {
        $stmt = $this->db->connexion()->query('SELECT * FROM utilisateur WHERE role = "Partenaire" ORDER BY nom, prenom');
        $partenaires = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // S'assurer que l'ID est correctement mappé
            if (isset($data['id'])) {
                $data['id_user'] = $data['id'];  // Mapper 'id' vers 'id_user' pour le modèle
            } elseif (isset($data['id_utilisateur'])) {
                $data['id_user'] = $data['id_utilisateur'];  // Ou mapper 'id_utilisateur' vers 'id_user'
            }
            $partenaires[] = new ModelePartenaire($data);
        }
        return $partenaires;
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
    public function getFicheByPartenaire(int $ref_user)
    {
        $sql = "
        SELECT 
            p.ref_user,
            p.poste,
            p.ref_fiche_entreprise,
            f.id_fiche_entreprise,
            f.nom_entreprise,
            f.adresse_entreprise,
            f.adresse_web
        FROM partenaire p
        LEFT JOIN fiche_entreprise f 
            ON p.ref_fiche_entreprise = f.id_fiche_entreprise
        WHERE p.ref_user = :ref_user
    ";

        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['ref_user' => $ref_user]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function affecterFichePartenaire(int $idUser, int $idFiche): bool
    {
        $pdo = $this->db->connexion();

        // Vérifier si le partenaire existe
        $checkSql = "SELECT ref_user FROM partenaire WHERE ref_user = :refUser";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([':refUser' => $idUser]);
        $exists = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($exists) {
            // Mise à jour si existe
            $sql = "UPDATE partenaire SET ref_fiche_entreprise = :idFiche WHERE ref_user = :idUser";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([':idFiche' => $idFiche, ':idUser' => $idUser]);
        } else {
            // Insertion si existe pas
            $sql = "INSERT INTO partenaire (ref_user, ref_fiche_entreprise) VALUES (:idUser, :idFiche)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([':idUser' => $idUser, ':idFiche' => $idFiche]);
        }
    }


}
