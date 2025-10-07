<?php

class EvenementUserRepository{
    private $db;
    public function __construct(){
        $this->db=New Config();
    }

    public function createEvenementUser(EvenementUser $eveUser){
        $req="INSERT INTO user_evenement (ref_user,ref_evenement, est_sperviseur) 
            VALUES (:user,:event,:estSuperviseur)";
        $stm=$this->db->connexion()->prepare($req);
        $stm->execute([
            "user"=>$eveUser->getRefUser(),
            "event"=>$eveUser->getRefEvenement(),
            "estSuperviseur"=>$eveUser->getEstSuperviseur()
        ]);


    }
    public function inscriptionEvenementUser(EvenementUser $eveUser){


    }
}