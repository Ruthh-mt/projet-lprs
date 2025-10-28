<?php

class EvenementUserRepository{
    private $db;
    public function __construct(){
        $this->db=New Config();
    }

    public function createEvenementUser(EvenementUser $eveUser){
        $req="INSERT INTO user_evenement (ref_user,ref_evenement,est_superviseur) 
            VALUES (:user,:event,:estSuperviseur)";
        $stm=$this->db->connexion()->prepare($req);
        $stm->execute([
            "user"=>$eveUser->getRefUser(),
            "event"=>$eveUser->getRefEvenement(),
            "estSuperviseur"=>$eveUser->getEstSuperviseur()
        ]);
    }
    public function verifDejaInscritEvenement(EvenementUser $eveUser){
        $req="SELECT * FROM user_evenement where ref_user=:user";
        $stm=$this->db->connexion()->prepare($req);
        $stm->execute(["user"=>$eveUser->getRefUser()]);
        $resultat=$stm->fetchAll();
        if(count($resultat)==0){
            return true;
        }
        else{
            return false;
        }

    }
    public function inscriptionEvenementUser(EvenementUser $eveUser){
        $req="INSERT INTO user_evenement (ref_user,ref_evenement,est_superviseur) 
            VALUES (:user,:event,:estSuperviseur) ";
        $stmt=$this->db->connexion()->prepare($req);
        $stmt->execute(array(
            "user"=>$eveUser->getRefUser(),
            "event"=>$eveUser->getRefEvenement(),
            "estSuperviseur"=>$eveUser->getEstSuperviseur()
            ));
    }
    public function getSuperviseur($id){
        $sql="SELECT ref_user FROM user_evenement WHERE ref_evenement=:id AND est_superviseur =:estSuperviseur";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute(["id"=>$id,
            "estSuperviseur"=>1]);
        return New EvenementUser([
            "refUser"=>$stmt->fetch()["ref_user"]
            ]
        );

    }
    public function desinscription($evenementUser){
        $req="DELETE FROM user_evenement WHERE ref_evenement=:evenement AND  ref_user=:user";
        $stm=$this->db->connexion()->prepare($req);
        $stm->execute(["evenement"=>$evenementUser->getRefEvenement(),
        "user"=>$evenementUser->getRefUser()]);
    }
    public function deleteUserEvenement($evenementUser){
        $req="DELETE FROM user_evenement WHERE ref_evenement=:evenement";
        $stm=$this->db->connexion()->prepare($req);
        $stm->execute(["evenement"=>$evenementUser->getRefEvenement()]);
    }
}