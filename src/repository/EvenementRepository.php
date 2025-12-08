<?php

class EvenementRepository
{

    private Config $db;

    public function __construct()
    {
        $this->db = new Config();
    }

    public function createEvenement(ModeleEvenement $evenement)
    {
        $sql = "INSERT INTO evenement (titre_eve,type_eve,desc_eve,lieu_eve,element_eve,nb_place,status,date_heure_evenement,est_valide) 
            VALUES (:titre, :type, :desc, :lieu, :element, :nbPlace,:status,:dateHeureEvenement,:estValide)";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute([
            'titre' => $evenement->getTitreEvenement(),
            'type' => $evenement->getTypeEvenement(),
            'desc' => $evenement->getDescEvenement(),
            'lieu' => $evenement->getLieuEvenement(),
            'element' => $evenement->getElementEvenement(),
            'nbPlace' => $evenement->getNbPlace(),
            'status' => $evenement->getStatus(),
            'dateHeureEvenement'=>$evenement->getDateHeureEvenement(),
            'estValide' => $evenement->getEstValide()

        ]);

        return $this->db->connexion()->lastInsertId();
    }

    public function getAllEvenement($limit,$perPage)
    {
        $sql = "SELECT * FROM evenement order by date_heure_evenement DESC LIMIT :limit,:perPage ";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute([
            'limit' => $limit,
            'perPage' => $perPage
        ]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAllEvenementNonValide($evenement)
    {
        $sql = "SELECT * FROM evenement WHERE est_valide=:estValide AND status=:status  order by date_heure_evenement DESC ";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute([
            'estValide' => $evenement->getEstValide(),
            'status' => $evenement->getStatus()
        ]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAnEvenement($evenement)
    {
        $sql = "SELECT * FROM evenement WHERE id_evenement=:id";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['id' => $evenement->getIdEvenement()]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function updateEvenement(ModeleEvenement $evenement)
    {
        $sql = "UPDATE evenement SET titre_eve=:titre, type_eve=:type,status=:status,date_heure_evenement=:dateHeureEvenemen, desc_eve=:desc, lieu_eve=:lieu, element_eve=:element,
         nb_place=:nbplace WHERE id_evenement=:id ";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute([
            'id' => $evenement->getIdEvenement(),
            'titre' => $evenement->getTitreEvenement(),
            'type' => $evenement->getTypeEvenement(),
            'status'=>$evenement->getStatus(),
            'dateHeureEvenement'=>$evenement->getDateHeureEvenement(),
            'desc' => $evenement->getDescEvenement(),
            'lieu' => $evenement->getLieuEvenement(),
            'element' => $evenement->getElementEvenement(),
            'nbplace' => $evenement->getNbPlace()
        ]);
        return $evenement->getIdEvenement();

    }
    public function countAllEvenement(){
        $sql = "SELECT COUNT(*) FROM evenement";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function validateEvenement(ModeleEvenement $evenement)
    {
        $sql = "Update evenement SET status=:status, est_valide=:estValide WHERE id_evenement=:id";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute([
            'id' => $evenement->getIdEvenement(),
            'status' => $evenement->getStatus(),
            'estValide' => $evenement->getEstValide()
        ]);
    }

    public function deleteEvenement($evenement)
    {
        $delete = "delete from evenement where id_evenement=:id";
        $stmt = $this->db->connexion()->prepare($delete);
        $stmt->execute(['id' => $evenement->getIdEvenement()]);
    }

    public function showEvenementAcceuil(){
        $sql="SELECT * FROM evenement  order by date_heure_evenement DESC LIMIT 5"; //AJOUTER LE SYSTEME DE DATE A LA PLACE DE L4ID
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
