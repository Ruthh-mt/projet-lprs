<?php

class AvisRepository
{
    private Config $db;

    public function __construct()
    {
        $this->db = new Config();
    }

    public function createAvis(ModeleAvis $avis)
    {
        $sql = "INSERT INTO avis_evenement (note, commentaire, date_avis, ref_user, ref_evenement)
                VALUES (:note, :commentaire, :dateAvis, :refUser, :refEvenement)";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute([
            'note' => $avis->getNote(),
            'commentaire' => $avis->getCommentaire(),
            'dateAvis' => $avis->getDateAvis(),
            'refUser' => $avis->getRefUser(),
            'refEvenement' => $avis->getRefEvenement()
        ]);
    }

    public function updateAvis(ModeleAvis $avis)
    {
        $sql = "UPDATE avis_evenement SET note = :note, commentaire = :commentaire, date_avis = :dateAvis
                WHERE id_avis = :idAvis AND ref_user = :refUser";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute([
            'note' => $avis->getNote(),
            'commentaire' => $avis->getCommentaire(),
            'dateAvis' => $avis->getDateAvis(),
            'idAvis' => $avis->getIdAvis(),
            'refUser' => $avis->getRefUser()
        ]);
    }

    public function getAllAvisByEvenement($idEvenement)
    {
        $sql = "SELECT avis_evenement.*, utilisateur.nom, utilisateur.prenom, utilisateur.avatar
                FROM avis_evenement
                INNER JOIN utilisateur ON avis_evenement.ref_user = utilisateur.id_user
                WHERE ref_evenement = :idEvenement
                ORDER BY date_avis DESC";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['idEvenement' => $idEvenement]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAvisByUserAndEvenement($idUser, $idEvenement)
    {
        $sql = "SELECT * FROM avis_evenement WHERE ref_user = :idUser AND ref_evenement = :idEvenement";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute([
            'idUser' => $idUser,
            'idEvenement' => $idEvenement
        ]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function hasUserAlreadyReviewed($idUser, $idEvenement)
    {
        $sql = "SELECT COUNT(*) FROM avis_evenement WHERE ref_user = :idUser AND ref_evenement = :idEvenement";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute([
            'idUser' => $idUser,
            'idEvenement' => $idEvenement
        ]);
        return $stmt->fetchColumn() > 0;
    }

    public function getAverageNote($idEvenement)
    {
        $sql = "SELECT AVG(note) FROM avis_evenement WHERE ref_evenement = :idEvenement";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['idEvenement' => $idEvenement]);
        return $stmt->fetchColumn();
    }

    public function getAverageNoteForAllEvenements()
    {
        $sql = "SELECT ref_evenement, AVG(note) as moyenne, COUNT(*) as nb_avis
                FROM avis_evenement
                GROUP BY ref_evenement";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
        $map = [];
        foreach ($results as $row) {
            $map[$row->ref_evenement] = $row;
        }
        return $map;
    }

    public function deleteAvis($idAvis, $idUser)
    {
        $sql = "DELETE FROM avis_evenement WHERE id_avis = :idAvis AND ref_user = :idUser";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute([
            'idAvis' => $idAvis,
            'idUser' => $idUser
        ]);
    }
}
