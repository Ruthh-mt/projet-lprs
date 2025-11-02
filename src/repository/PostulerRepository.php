<?php
require_once __DIR__ . '/../bdd/config.php';
require_once __DIR__ . '/../modele/Postuler.php';

class PostulerRepository
{
    private $db;

    public function __construct()
    {
        $this->db = new Config();
    }

    public function insert(Postuler $postuler): bool
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
            $candidatures[] = new Postuler($row);
        }
        return $candidatures;
    }

    public function findByUserAndOffre(int $refUser, int $refOffre): ?Postuler
    {
        $sql = "SELECT * FROM postuler WHERE ref_user = :refUser AND ref_offre = :refOffre";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['refUser' => $refUser, 'refOffre' => $refOffre]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new Postuler($row) : null;
    }

    public function findByUser(int $refUser): array
    {
        $sql = "SELECT * FROM postuler WHERE ref_user = :refUser";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['refUser' => $refUser]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $candidatures = [];
        foreach ($rows as $row) {
            $candidatures[] = new Postuler($row);
        }
        return $candidatures;
    }

    public function delete(int $refUser, int $refOffre): bool
    {
        $sql = "DELETE FROM postuler WHERE ref_user = :refUser AND ref_offre = :refOffre";
        $stmt = $this->db->connexion()->prepare($sql);
        return $stmt->execute([
            'refUser'  => $refUser,
            'refOffre' => $refOffre
        ]);
    }


}
