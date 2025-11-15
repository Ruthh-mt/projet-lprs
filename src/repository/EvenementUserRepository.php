<?php

class EvenementUserRepository{
    private $db;
    public function __construct(){
        $this->db=New Config();
    }

    public function createEvenementUser(ModeleEvenementUser $eveUser){
        $req="INSERT INTO user_evenement (ref_user,ref_evenement,est_superviseur) 
            VALUES (:user,:event,:estSuperviseur)";
        $stm=$this->db->connexion()->prepare($req);
        $stm->execute([
            "user"=>$eveUser->getRefUser(),
            "event"=>$eveUser->getRefEvenement(),
            "estSuperviseur"=>$eveUser->getEstSuperviseur()
        ]);
    }
    public function verifDejaInscritEvenement(ModeleEvenementUser $eveUser){
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
    public function inscriptionEvenementUser(ModeleEvenementUser $eveUser){
        $req="INSERT INTO user_evenement (ref_user,ref_evenement,est_superviseur) 
            VALUES (:user,:event,:estSuperviseur) ";
        $stmt=$this->db->connexion()->prepare($req);
        $stmt->execute(array(
            "user"=>$eveUser->getRefUser(),
            "event"=>$eveUser->getRefEvenement(),
            "estSuperviseur"=>$eveUser->getEstSuperviseur()
            ));
    }
    public function addSuperviseur( $eveUser){
        $sql="INSERT INTO user_evenement (ref_user,ref_evenement,est_superviseur) 
            VALUES (:user,:event,:estSuperviseur)";
        $stm=$this->db->connexion()->prepare($sql);
        $stm->execute(array(
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
        return $stmt->fetchAll(PDO::FETCH_COLUMN);

    }
    public function getAllInscritsByEvenement($eveuser){
        $sql="SELECT user_evenement.ref_user, utilisateur.nom,utilisateur.prenom, utilisateur.role FROM user_evenement inner join utilisateur on user_evenement.ref_user =utilisateur.id_user WHERE est_superviseur =:estSuperviseur AND ref_evenement =:evenement";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute(["estSuperviseur"=>$eveuser->getEstSuperviseur(),
            "evenement"=>$eveuser->getRefEvenement()]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function countAllInscritsByEvenement($id){
        $sql="SELECT COUNT(ref_user) FROM user_evenement WHERE est_superviseur =:estSuperviseur AND ref_evenement =:evenement";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute(["estSuperviseur"=>0,
            "evenement"=>$id]);
        return $stmt->fetchColumn();
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
    public function getRoleSuperviseur($evenementUser){
        $sql="SELECT utilisateur.role FROM user_evenement INNER JOIN  utilisateur ON post.ref_user = utilisateur.id_user WHERE ref_user=:user AND ref_evenement=:evenement AND est_superviseur =:estSuperviseur ";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute(["user"=>$evenementUser->getRefUser(),
            "evenement"=>$evenementUser->getRefEvenement(),
            "estSuperviseur"=>$evenementUser->getEstSuperviseur()]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}