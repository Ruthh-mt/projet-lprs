<?php
require_once __DIR__ . "/../bdd/config.php";
require_once __DIR__ . "/../modele/ModeleFicheEntreprise.php";

class FicheRepository
{
    private $db;

    public function __construct()
    {
        $this->db = new Config();
    }

    public function findAllFiches(): array
    {
        $sql = "SELECT * FROM fiche_entreprise ORDER BY nom_entreprise ASC";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $entreprises = [];
        foreach ($rows as $row) {
            $entreprises[] = new ModeleFicheEntreprise($row);
        }
        return $entreprises;
    }

    public function getFicheByOffre($id_offre){
        $sql = "
        SELECT 
           o.* , f.*
        FROM offre o
        INNER JOIN fiche_entreprise f 
            ON o.ref_fiche = f.id_fiche_entreprise
        WHERE o.id_offre = :id_offre
    ";

        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['id_offre' => $id_offre]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function createFiche(ModeleFicheEntreprise $fiche): bool
    {
        $sql = "INSERT INTO fiche_entreprise (nom_entreprise, adresse_entreprise, adresse_web)
                VALUES (:nom, :adresse, :web)";
        $stmt = $this->db->connexion()->prepare($sql);

        return $stmt->execute([
            'nom'     => $fiche->getNomEntreprise(),
            'adresse' => $fiche->getAdresseEntreprise(),
            'web'     => $fiche->getAdresseWeb(),
            'adresseWeb'     => $fiche->getAdresseWeb()
        ]);
    }
    public function updateFiche(ModeleFicheEntreprise $fiche): bool
    {
        $sql = "UPDATE fiche_entreprise 
                SET nom_entreprise = :nom,
                    adresse_entreprise = :adresse,
                    adresse_web = :web
                WHERE id_fiche_entreprise = :id";
        $stmt = $this->db->connexion()->prepare($sql);

        return $stmt->execute([
            'nomEntreprise'     => $fiche->getNomEntreprise(),
            'adresseEntreprise' => $fiche->getAdresseEntreprise(),
            'adresseWeb'     => $fiche->getAdresseWeb(),
            'idFiche'      => $fiche->getIdFicheEntreprise()
        ]);
    }

    public function deleteFiche(int $id): bool
    {
        $sql = "DELETE FROM fiche_entreprise WHERE id_fiche_entreprise = :id";
        $stmt = $this->db->connexion()->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
