<?php
class EvenementRepository{
    private $db;
    public function __construct(){
        $this->db=New Config();
    }

    public function createEvenement(Evenement $evenement){
        $sql="INSERT INTO evenement (titre_eve,type_eve,desc_eve,lieu_eve,element_eve,nb_place) 
            VALUES (:titre, :type, :desc, :lieu, :element, :nbPlace)";
        $stmt=$this->db->connexion()->prepare($sql);
        $req=$stmt->execute([
           'titre'=>$evenement->getTitreEvenement(),
           'type' =>$evenement->getTypeEvenement(),
            'desc'=>$evenement->getDescEvenement(),
            'lieu'=>$evenement->getLieuEvenement(),
            'element'=>$evenement->getElementEvenement(),
            'nbPlace'=>$evenement->getNbPlace()
        ]);
        if ($req) {
            return true;
        } else {
            return false;
        }
    }

}
