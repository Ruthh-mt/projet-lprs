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
    public function updateCandidature($ref_user, $ref_offre, $motivation)
    {
        try {
            $sql = "UPDATE postuler 
                SET motivation = :motivation
                WHERE ref_user = :ref_user 
                AND ref_offre = :ref_offre";


            $pdo = $this->db->connexion();
            $stmt = $pdo->prepare($sql);

            $params = [
                ':motivation' => $motivation,
                ':ref_user'   => $ref_user,
                ':ref_offre'  => $ref_offre
            ];

            $ok = $stmt->execute($params);

            if ($ok) {
                return true;
            }

        } catch (PDOException $e) {
            $this->lastError = "Erreur PDO: " . $e->getMessage();
            error_log("PDO Exception: " . $e->getMessage());
            return false;
        }
    }

    public function getCandidat(int $id_user){
        $sql = "SELECT u.nom , u.prenom FROM postuler p inner join offre o inner join utilisateur u
         on p.ref_offre = o.id_offre WHERE p.ref_user = :ref_user";
         $stmt = $this->db->connexion()->prepare($sql);
         $stmt->execute(['ref_user' => $id_user]);
         $result = $stmt->fetch(PDO::FETCH_ASSOC);
         return $result;
    }
}
