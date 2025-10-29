<?php

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
        $sql = "SELECT * FROM offre o inner join crudPostuler p inner join utilisateur u
         on o.id_offre = p.ref_offre 
         on p.ref_user = u.id_user
         WHERE id_user =?";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['id' => $user->getIdUser()]);
        $req = $stmt->fetchAll();

        return $req[0];
    }

    public function updateEvenement(ModeleEvenement $evenement)
    {
        $sql = "UPDATE evenement SET titre_eve=:titre, type_eve:type, desc_eve=:desc, lieu_eve=:lieu, element_eve=:element,
         nb_place=:nbplace WHERE id_evenement=:id ";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute([
            'id' => $evenement->getIdEvenement(),
            'titre' => $evenement->getTitreEvenement(),
            'type' => $evenement->getTypeEvenement(),
            'desc' => $evenement->getDescEvenement(),
            'lieu' => $evenement->getLieuEvenement(),
            'element' => $evenement->getElementEvenement(),
            'nbPlace' => $evenement->getNbPlace()
        ]);

        return $this->db->connexion()->lastInsertId();
    }
}