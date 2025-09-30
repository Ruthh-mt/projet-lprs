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
        $stmt->execute([
           'titre'=>$evenement->getTitreEve(),
           'type' =>$evenement->getTypeEvenement(),
            'desc'=>$evenement->getDescEve(),
            'lieu'=>$evenement->getLieuEve(),
            'element'=>$evenement->getElementEve(),
            'nbPlace'=>$evenement->getNbPlace()
        ]);
    }

}
