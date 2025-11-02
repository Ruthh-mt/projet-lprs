<?php
declare(strict_types=1);
class OffreRepository
{
    private $db;
    public function __construct()
    {
        $this->db=NEW Config();
    }
    public function createOffre(Offre $offre): bool
    {
        $sql = "INSERT INTO offre (titre, description, mission, salaire, type, etat, ref_fiche)
            VALUES (:titre, :description, :mission, :salaire, :type, :etat, :ref_fiche)";
        $stmt = $this->db->connexion()->prepare($sql);

        return $stmt->execute([
            'titre'       => $offre->getTitre(),
            'description' => $offre->getDescription(),
            'mission'     => $offre->getMission(),
            'salaire'     => $offre->getSalaire(),
            'type'        => $offre->getType(),
            'etat'        => $offre->getEtat(),
            'ref_fiche'   => $offre->getRefFiche(),
        ]);
    }
    public function getAllOffres()
    {
        $sql="SELECT * FROM offre";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAnOffre(int $idOffre): ?Offre
    {
        $sql = "SELECT * FROM offre WHERE id_offre = :id";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['id' => $idOffre]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }
        $offre = new Offre([
            'idOffre'   => $row['id_offre'],
            'titre'     => $row['titre'],
            'description' => $row['description'],
            'mission'   => $row['mission'],
            'salaire'   => $row['salaire'],
            'type'      => $row['type'],
            'etat'      => $row['etat'],
            'refFiche'  => $row['ref_fiche']
        ]);

        return $offre;
    }

    public function updateOffre(Offre $offre): bool
    {
        $sql = "UPDATE offre 
            SET 
                titre = :titre,
                description = :description,
                mission = :mission,
                salaire = :salaire,
                type = :type,
                etat = :etat,
                ref_fiche = :refFiche
            WHERE id_offre = :idOffre";

        $stmt = $this->db->connexion()->prepare($sql);

        return $stmt->execute([
            'titre'     => $offre->getTitre(),
            'description' => $offre->getDescription(),
            'mission'   => $offre->getMission(),
            'salaire'   => $offre->getSalaire(),
            'type'      => $offre->getType(),
            'etat'      => $offre->getEtat(),
            'refFiche'  => $offre->getRefFiche(),
            'idOffre'   => $offre->getIdOffre()
        ]);
    }


}