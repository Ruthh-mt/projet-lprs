<?php
require_once __DIR__ . '/../bdd/config.php';
require_once __DIR__ . '/../modele/ModeleFormation.php';

class FormationRepository
{
    private PDO $pdo;
    private string $table = 'formation';

    public function __construct()
    {
        $config = new Config();
        $this->pdo = $config->connexion();
    }

    public function create(ModeleFormation $m): int
    {
        $sql = "INSERT INTO {$this->table} (nom) VALUES (:nom)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nom' => $m->nom]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(ModeleFormation $m): bool
    {
        if ($m->id_formation === null) {
            throw new InvalidArgumentException('ID requis pour update.');
        }
        $sql = "UPDATE {$this->table} SET nom = :nom WHERE id_formation = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':nom' => $m->nom, ':id' => $m->id_formation]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id_formation = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function find(int $id): ?ModeleFormation
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_formation = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? ModeleFormation::fromArray($row) : null;
    }

    public function findAll(?int $limit = 100, int $offset = 0): array
    {
        if ($limit === null) {
            $sql = "SELECT * FROM {$this->table} ORDER BY nom ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        } else {
            $sql = "SELECT * FROM {$this->table} ORDER BY nom ASC LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $out = [];
        foreach ($rows as $r) {
            $out[] = ModeleFormation::fromArray($r);
        }
        return $out;
    }

    public function count(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM {$this->table}");
        return (int)$stmt->fetchColumn();
    }
}
