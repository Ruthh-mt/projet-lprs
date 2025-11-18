<?php


require_once __DIR__ . '/../bdd/config.php';
require_once __DIR__ . '/../modele/ModeleFormation.php';

class FormationRepository
{
    private PDO $db;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo instanceof PDO) {
            $this->db = $pdo;
        } else {
            $this->db = (new Config())->connexion();
        }
    }

    public function getById(int $id_formation): ?array
    {
        $sql = "SELECT * FROM formation WHERE id_formation = :id_formation";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_formation' => $id_formation]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function findAll(?string $orderBy = null): array
    {
        $sql = "SELECT * FROM formation";

        $allowedOrder = ['id_formation', 'nom'];
        if ($orderBy !== null && in_array($orderBy, $allowedOrder, true)) {
            $sql .= " ORDER BY " . $orderBy;
        }

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_OBJ) ?: [];
    }

    public function create(ModeleFormation $formation): bool
    {
        $sql = "INSERT INTO formation (nom) VALUES (:nom)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'nom' => $formation->getNom(),
        ]);
    }

    public function update(int $id_formation, array $data): bool
    {
        $set    = [];
        $params = ['id_formation' => $id_formation];

        if (array_key_exists('nom', $data)) {
            $set[]        = "nom = :nom";
            $params['nom'] = $data['nom'];
        }

        if (!$set) {
            return false;
        }

        $sql  = "UPDATE formation SET " . implode(', ', $set) . " WHERE id_formation = :id_formation";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($params);
    }

    public function delete(int $id_formation): bool
    {
        $sql = "DELETE FROM formation WHERE id_formation = :id_formation";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':id_formation', $id_formation, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
