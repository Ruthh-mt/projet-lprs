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
    public function updateOffre(ModeleOffre $offre){
        $sql = "UPDATE offre
                SET  titre=?,description=?, mission=?,salaire=? , type =?,etat=? ,ref_fiche=? 
                WHERE id_offre=? ";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute([
            $offre -> getIdOffre() ,
            $offre->getTitreOffre(),
            $offre->getDescription(),
            $offre->getMission(),
            $offre->getSalaire(),
            $offre -> getTypeContrat() ,
            $offre->getEtat(),
            $offre->getRefFiche()
        ]);
    }
    public function getOffreById($id){
        $sql = "SELECT * FROM offre WHERE id_offre = :id_offre";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['id_offre' => $id]);
        $offre = $stmt->fetch(PDO::FETCH_ASSOC);
        return $offre;
    }

    public function getAllOffre()
    {
        $sql = "SELECT * FROM offre o inner join fiche_entreprise f on o.ref_fiche = f.id_fiche_entreprise";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute();
        $offres = $stmt->fetchAll();
        return $offres;
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