<?php

class ReponseRepository
{
    private $db;

    public function __construct()
    {
        $this->db = new Config();
    }

    public function getAllReponsebyPostId($idPost)
    {
        $sql = "SELECT * FROM reponse WHERE ref_post=:refPost ORDER BY date_heure DESC";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(["refPost" => $idPost]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function createReponse($reponse)
    {
        $ajout = "Insert into reponse (contenu_reponse,date_heure,ref_user,ref_post) VALUES (:contenuReponse,:dateHeureReponse,:refUser,:refPost)";
        $stmt = $this->db->connexion()->prepare($ajout);
        $stmt->execute([
            'contenuReponse' => $reponse->getContenuReponse(),
            'dateHeureReponse' => $reponse->getDateHeureReponse(),
            'refUser' => $reponse->getRefUser(),
            'refPost' => $reponse->getRefPost()
        ]);
    }

    public function updateReponse($reponse)
    {
        $ajout = "UPDATE reponse SET contenu_reponse =:contenuReponse WHERE ref_user =:refUser  AND id_reponse =:idReponse";
        $stmt = $this->db->connexion()->prepare($ajout);
        $stmt->execute([
            'contenuReponse' => $reponse->getContenuReponse(),
            'refUser' => $reponse->getRefUser(),
            'idReponse' => $reponse->getIdReponse()
        ]);
    }

    public function findUsernameReponse($reponse)
    {
        $sql = "SELECT utilisateur.nom,utilisateur.prenom FROM reponse INNER JOIN  utilisateur ON reponse.ref_user = utilisateur.id_user WHERE ref_post=:refPost AND id_reponse=:idReponse";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(["refPost" => $reponse->getRefPost(),
            "idReponse" => $reponse->getIdReponse()
        ]);
        return $stmt->fetch();
    }
    public function deleteReponse($reponse)
    {
        $delete = "delete from reponse where id_reponse=:id";
        $stmt = $this->db->connexion()->prepare($delete);
        $stmt->execute(['id' => $reponse->getIdReponse()]);
    }
    public function getDeleteAllReponseByPost($reponse){
        $delete = "delete from reponse where ref_post=:id";
        $stmt = $this->db->connexion()->prepare($delete);
        $stmt->execute(['id' => $reponse->getRefPost()]);
    }
    public function getAllReponseByUser($reponse){
        $delete = "SELECT * FROM reponse where ref_user=:id ORDER BY date_heure DESC";
        $stmt = $this->db->connexion()->prepare($delete);
        $stmt->execute(['id' => $reponse->getRefUser()]);
    }
}