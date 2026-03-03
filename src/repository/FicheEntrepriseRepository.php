<?php
require_once __DIR__ . '/../bdd/config.php';

class FicheEntrepriseRepository
{
    private $db;

    public function __construct()
    {
        $this->db = new Config();
    }

    public function findFicheByWeb(string $web): ?array
    {
        $pdo = $this->db->connexion();
        $sql = "SELECT id_fiche_entreprise FROM fiche_entreprise WHERE adresse_web = :web";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['web' => $web]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function findFicheByOffre(int $id_offre): ?object
    {
        $pdo = $this->db->connexion();
        $sql = "SELECT fe.* FROM fiche_entreprise fe 
                JOIN offre o ON fe.id_fiche_entreprise = o.ref_fiche
                WHERE o.id_offre = :id_offre";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_offre' => $id_offre]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        return $result ?: null;
    }

    public function createFiche(array $data): ?int
    {
        $pdo = $this->db->connexion();
        $sql = "INSERT INTO fiche_entreprise (nom_entreprise, adresse_entreprise, adresse_web) 
                VALUES (:nom, :adresse, :web)";
        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute([
            'nom' => $data['nom'],
            'adresse' => $data['adresse'],
            'web' => $data['web']
        ]);

        if ($ok) {

            return (int)$pdo->lastInsertId();
        }
        return null;
    }
    public function getAllFicheEntreprises(){
        $pdo = $this->db->connexion();
        $sql = "SELECT * FROM fiche_entreprise";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function getFicheEntrepriseById(int $id_fiche_entreprise): ?array
    {
        $pdo = $this->db->connexion();
        $sql = "SELECT * FROM fiche_entreprise WHERE id_fiche_entreprise = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id_fiche_entreprise]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }
    
    public function deleteFicheEntreprise(int $id_fiche_entreprise): bool
    {
        $pdo = $this->db->connexion();
        $sql = "DELETE FROM fiche_entreprise WHERE id_fiche_entreprise = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(['id' => $id_fiche_entreprise]);
    }

    public function findFicheByUser(int $id){
        $pdo = $this->db->connexion();
        $sql = "SELECT * FROM fiche_entreprise f inner join partenaire p
         on f.id_fiche_entreprise = p.ref_fiche_entreprise
         WHERE ref_user = :ref_user";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['ref_user' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    public function getFicheById(int $id): ?array {
        $pdo = $this->db->connexion();
        $sql = "SELECT * FROM fiche_entreprise WHERE id_fiche_entreprise = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);

    }

    public function updateFiche(int $id, array $data): bool {
        $pdo = $this->db->connexion();
        $sql = "UPDATE fiche_entreprise SET 
                nom_entreprise = :nom,
                adresse_entreprise = :adresse,
                adresse_web = :web
                WHERE id_fiche_entreprise = :id";
                
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'nom' => $data['nom'],
            'adresse' => $data['adresse'],
            'web' => $data['web'],
            'id' => $id
        ]);
    }

}
