<?php

class FicheEntrepriseRepository
{
    private $db;

    public function __construct()
    {
        $this->db = new Config();
    }

    public function findFicheByWeb(string $web): ?array
    {
        $pdo = $this->db->connexion();
        $sql = "SELECT id_fiche_entreprise FROM fiche_entreprise WHERE adresse_web = :web";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['web' => $web]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function createFiche(array $data): ?int
    {
        $pdo = $this->db->connexion();
        $sql = "INSERT INTO fiche_entreprise (nom_entreprise, adresse_entreprise, adresse_web) 
                VALUES (:nom, :adresse, :web)";
        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute([
            'nom' => $data['nom'],
            'adresse' => $data['adresse'],
            'web' => $data['web']
        ]);

        if ($ok) {

            return (int)$pdo->lastInsertId();
        }
        return null;
    }
}
