<?php
require_once(__DIR__ . '/../../src/bdd/config.php');

class OffreRepository
{

    private Config $db;

    public function __construct()
    {
        $this->db = new Config();
    }

    public function createOffre(ModeleOffre $offre)
    {
        $sql = "INSERT INTO offre (titre,description, mission,salaire, type, etat, ref_fiche) 
            VALUES (:titreOffre, :description, :mission, :salaire, :typeContrat, :etat, :refFiche)";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute([
            'titreOffre' => $offre->getTitreOffre(),
            'description' => $offre->getDescription(),
            'mission' => $offre->getMission(),
            'salaire' => $offre->getSalaire(),
            'typeContrat' => $offre->getTypeContrat(),
            'etat' => $offre ->getEtat(),
            'refFiche' => $offre->getRefFiche()
        ]);
        return $this->db->connexion()->lastInsertId();
    }
    public function deleteOffre($id)
    {
        $sql = "DELETE FROM offre WHERE id_offre = :id_offre";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['id_offre' => $id]) ;
    }

    public function getAllOffre()
    {
        $sql = "SELECT * FROM offre o inner join fiche_entreprise f on o.ref_fiche = f.id_fiche_entreprise";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute();
        $offres = $stmt->fetchAll();
        return $offres;
    }

    public function getOffreById($userId)
    {
        $sql = "SELECT * FROM offre o 
                INNER JOIN postuler p ON o.id_offre = p.ref_offre 
                INNER JOIN utilisateur u ON p.ref_user = u.id_user 
                WHERE id_user = :id";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['id' => $userId]);
        $req = $stmt->fetchAll();

        return $req[0] ?? null;
    }
    public function getOffresParenaire(int $id)
    {
        $sql = "
        SELECT 
            o.id_offre,
            o.titre,
            o.description,
            o.mission,
            o.salaire,
            o.type,
            o.etat,
            f.nom_entreprise,
            f.adresse_entreprise,
            f.adresse_web
        FROM partenaire p
        INNER JOIN fiche_entreprise f ON p.ref_fiche_entreprise = f.id_fiche_entreprise
        INNER JOIN offre o ON o.ref_fiche = f.id_fiche_entreprise
        WHERE p.ref_user = :id_user
    ";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['id_user' => $id]);

        $req = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $req;
    }
    public function getOffresAlumni(int $id)
    {
        $sql = "
        SELECT 
            o.id_offre,
            o.titre,
            o.description,
            o.mission,
            o.salaire,
            o.type,
            o.etat,
            f.nom_entreprise,
            f.adresse_entreprise,
            f.adresse_web
        FROM alumni a
        INNER JOIN fiche_entreprise f ON a.ref_fiche_entreprise = f.id_fiche_entreprise
        INNER JOIN offre o ON o.ref_fiche = f.id_fiche_entreprise
        WHERE a.ref_user = :id_user
    ";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['id_user' => $id]);

        $req = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $req;
    }


}