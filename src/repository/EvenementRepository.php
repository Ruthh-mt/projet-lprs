<?php
class EvenementRepository{

    private Config $db;
    public function __construct(){
        $this->db=New Config();
    }

    public function createEvenement(ModeleEvenement $evenement){
        $sql="INSERT INTO evenement (titre_eve,type_eve,desc_eve,lieu_eve,element_eve,nb_place,status,est_valide) 
            VALUES (:titre, :type, :desc, :lieu, :element, :nbPlace,:status,:estValide)";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute([
           'titre'=>$evenement->getTitreEvenement(),
           'type' =>$evenement->getTypeEvenement(),
            'desc'=>$evenement->getDescEvenement(),
            'lieu'=>$evenement->getLieuEvenement(),
            'element'=>$evenement->getElementEvenement(),
            'nbPlace'=>$evenement->getNbPlace(),
            'status'=>$evenement->getStatus(),
            'estValide'=>$evenement->getEstValide()

        ]);

        return $this->db->connexion()->lastInsertId();
    }
    public function getAllEvenement()
    {
        $sql="SELECT * FROM evenement";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function getAllEvenementNonValidated()
    {
        $sql="SELECT * FROM evenement WHERE est_valide=:estValide AND status=:status ";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute([
            'estValide'=>0,
            'status'=>"en attente"
        ]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function getAnEvenement($evenement){
        $sql="SELECT * FROM evenement WHERE id_evenement=:id";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute(['id'=>$evenement->getIdEvenement()]);
        $req=$stmt->fetch();
        $evenement->setIdEvenement($req['id_evenement']);
        $evenement->setTitreEvenement($req['titre_eve']);
        $evenement->setTypeEvenement($req['type_eve']);
        $evenement->setDescEvenement($req['desc_eve']);
        $evenement->setLieuEvenement($req['lieu_eve']);
        $evenement->setElementEvenement($req['element_eve']);
        $evenement->setNbPlace($req['nb_place']);
        $evenement->setEstValide($req['est_valide']);
        $evenement->setStatus($req['status']);
        return $evenement;
    }
    public function updateEvenement(ModeleEvenement $evenement){
        $sql="UPDATE evenement SET titre_eve=:titre, type_eve=:type, desc_eve=:desc, lieu_eve=:lieu, element_eve=:element,
         nb_place=:nbplace WHERE id_evenement=:id ";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute([
            'id' => $evenement->getIdEvenement(),
            'titre'=>$evenement->getTitreEvenement(),
            'type'=>$evenement->getTypeEvenement(),
            'desc'=>$evenement->getDescEvenement(),
            'lieu'=>$evenement->getLieuEvenement(),
            'element'=>$evenement->getElementEvenement(),
            'nbplace'=>$evenement->getNbPlace()
        ]);
        return $evenement->getIdEvenement();

    }
    public function validateEvenement(ModeleEvenement $evenement){
        $sql="Update evenement SET status=:status, est_valide=:estValide WHERE id_evenement=:id";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute([
            'id' => $evenement->getIdEvenement(),
            'status'=>$evenement->getStatus(),
            'estValide'=>$evenement->getEstValide()
        ]);
    }

    public function deleteEvenement($evenement)
    {
        $delete="delete from evenement where id_evenement=:id";
        $stmt=$this->db->connexion()->prepare($delete);
        $stmt->execute(['id'=>$evenement->getIdEvenement()]);
    }

}
