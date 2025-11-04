<?php
require_once __DIR__ . '/../bdd/config.php';
require_once __DIR__ . '/../modele/ModelePostuler.php';

class PostulerRepository
{
    private $db;

    public function __construct()
    {
        $this->db = new Config();
    }

    public function insert(ModelePostuler $postuler): bool
    {
        $sql = "INSERT INTO postuler (ref_user, ref_offre, motivation)
                VALUES (:refUser, :refOffre, :motivation)";
        $stmt = $this->db->connexion()->prepare($sql);

        return $stmt->execute([
            'refUser'     => $postuler->getRefUser(),
            'refOffre'    => $postuler->getRefOffre(),
            'motivation'  => $postuler->getMotivation(),
        ]);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM postuler";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $candidatures = [];
        foreach ($rows as $row) {
            $candidatures[] = new ModelePostuler($row);
        }
        return $candidatures;
    }

    public function deleteCandidature(int $refUser, int $refOffre): bool
    {
        $sql = "DELETE FROM postuler WHERE ref_user = :refUser AND ref_offre = :refOffre";
        $stmt = $this->db->connexion()->prepare($sql);
        $ok = $stmt->execute([$refUser, $refOffre]);
        if ($ok){
            return true;
        }
        else{
            return false;
        }
    }
    public function findCandidatures($ref_user){
        $sql = "SELECT * FROM postuler p inner join offre o 
         on p.ref_offre = o.id_offre WHERE ref_user = :ref_user";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['ref_user' => $ref_user]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function findOffreAndUser($ref_user,$ref_offre){
        $sql = "SELECT * FROM postuler p inner join offre o
        on p.ref_offre = o.id_offre WHERE
        ref_user = :ref_user AND ref_offre = :ref_offre" ;
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['ref_user' => $ref_user, 'ref_offre' => $ref_offre]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    public function updateCandidature($lettre_motivation){
        $sql="UPDATE postuler SET
                     motivation=:motivation ";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute([
            'motivation' => $lettre_motivation
        ]);

    }

}
