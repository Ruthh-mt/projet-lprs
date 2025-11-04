<?php
require_once ('../../src/bdd/config.php');

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
            VALUES (:titre, :description, :mission, :salaire, :type, :etat, :ref_fiche)";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute([
            'titre' => $offre->getTitreOffre(),
            'description' => $offre->getDescription(),
            'mission' => $offre->getMission(),
            'salaire' => $offre->getSalaire(),
            'type' => $offre->getTypeContrat(),
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
        $sql = "SELECT * FROM offre o inner join fiche_entreprise f
    on o.ref_fiche = f.id_fiche_entreprise";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute();
        $offres = $stmt->fetchAll();
        return $offres;
    }

    public function getOffreById($user)
    {
        $sql = "SELECT * FROM offre o inner join postuler p inner join utilisateur u
         on o.id_offre = p.ref_offre 
         on p.ref_user = u.id_user
         WHERE id_user =:id";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['id' => $user->getIdUser()]);
        $req = $stmt->fetchAll();

        return $req[0];
    }

}