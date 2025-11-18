<?php

class ReponseRepository
{
    private $db;
    public function __construct(){
        $this->db=New Config();
    }

    public function getAllReponsebyPostId($idPost){
        $sql="SELECT * FROM reponse WHERE ref_post=:refPost ORDER BY date_heure_reponse";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute(["refPost"=>$idPost]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function createReponse($reponse){
        $ajout="Insert into reponse (contenu_reponse,date_heure_reponse,ref_user,ref_post) VALUES (:contenuReponse,:dateHeureReponse,:refUser,:refPost)";
        $stmt=$this->db->connexion()->prepare($ajout);
        $stmt->execute([
            'contenuReponse'=>$reponse->getContenuReponse(),
            'dateHeureReponse'=>$reponse->getDateHeureReponse(),
            'refUser'=>$reponse->getRefUser(),
            'refPost'=>$reponse->getRefPost()
        ]);
    }
    public function updateReponse($reponse){
        $ajout="UPDATE reponse SET contenu_reponse =:contenuReponse,date_heure_reponse =:dateHeureReponse WHERE ref_user =:refUser  AND id_reponse =:idReponse AND ref_post=:refPost";
        $stmt=$this->db->connexion()->prepare($ajout);
        $stmt->execute([
            'contenuReponse'=>$reponse->getContenuReponse(),
            'dateHeureReponse'=>$reponse->getDateHeureReponse(),
            'refUser'=>$reponse->getRefUser(),
            'idReponse'=>$reponse->getIdReponse(),
            'refPost'=>$reponse->getRefPost()
        ]);
    }
    public function findUsernameReponse($reponse){
        $sql="SELECT utilisateur.nom,utilisateur.prenom FROM reponse INNER JOIN  utilisateur ON reponse.ref_user = utilisateur.id_user WHERE ref_post=:refPost AND id_reponse=:idReponse";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute(["refPost"=>$reponse->getRefPost(),
            "idReponse"=>$reponse->getIdReponse()
        ]);
        return $stmt->fetch();
    }
}