<?php
require_once __DIR__ . '/../modele/userModel.php';
class UserRepository
{
    private $db;
    public function __construct()
    {
        $this->db = new Config();
    }
    public function getUserByEmail(string $email): ?UserModel
    {
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToUser($row) : null;
    }
    public function inscription(UserModel $user): int
    {
        $sql = 'INSERT INTO utilisateur(nom, prenom, email, mdp, role)
                VALUES (:nom, :prenom, :email, :mdp, :role)';
        $pdo = $this->db->connexion();
        $req = $pdo->prepare($sql);
        $req->execute([
            'nom'    => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'email'  => $user->getEmail(),
            'mdp'    => $user->getMdp(),
            'role'   => $user->getRole()
        ]);
        return (int)$pdo->lastInsertId();
    }
    public function insertEtudiant(int $refUser, ?string $cv, string $anneePromo, int $refFormation): void
    {
        $sql = "INSERT INTO etudiant (ref_user, cv, annee_promo, ref_formation)
                VALUES (:ref_user, :cv, :annee_promo, :ref_formation)";
        $this->db->connexion()->prepare($sql)->execute([
            'ref_user'      => $refUser,
            'cv'            => $cv,
            'annee_promo'   => $anneePromo,
            'ref_formation' => $refFormation,
        ]);
    }
    public function insertAlumni(int $refUser, ?string $cv, string $anneePromo, ?string $poste = null, ?int $refFiche = null): void
    {
        $sql = "INSERT INTO alumni (ref_user, cv, annee_promo, poste, ref_fiche_entreprise)
                VALUES (:ref_user, :cv, :annee_promo, :poste, :ref_fiche)";
        $this->db->connexion()->prepare($sql)->execute([
            'ref_user'    => $refUser,
            'cv'          => $cv,
            'annee_promo' => $anneePromo,
            'poste'       => $poste,
            'ref_fiche'   => $refFiche,
        ]);
    }
    public function insertProfesseur(int $refUser, string $specialite): void
    {
        $sql = "INSERT INTO professeur (ref_user, specialite)
                VALUES (:ref_user, :specialite)";
        $this->db->connexion()->prepare($sql)->execute([
            'ref_user'   => $refUser,
            'specialite' => $specialite,
        ]);
    }
    public function insertPartenaire(int $refUser, ?string $cv, string $poste, ?int $refFiche = null): void
    {
        $sql = "INSERT INTO partenaire (ref_user, cv, poste, ref_fiche_entreprise)
                VALUES (:ref_user, :cv, :poste, :ref_fiche)";
        $this->db->connexion()->prepare($sql)->execute([
            'ref_user'  => $refUser,
            'cv'        => $cv,
            'poste'     => $poste,
            'ref_fiche' => $refFiche,
        ]);
    }

    public function getOrCreateFormationIdByName(string $nom): int
    {
        $pdo = $this->db->connexion();
        $sel = $pdo->prepare("SELECT id_formation FROM formation WHERE nom = :nom");
        $sel->execute(['nom' => $nom]);
        $id = $sel->fetchColumn();
        if ($id) return (int)$id;

        $ins = $pdo->prepare("INSERT INTO formation (nom) VALUES (:nom)");
        $ins->execute(['nom' => $nom]);
        return (int)$pdo->lastInsertId();
    }
    public function findAll(): array
    {
        $sql = "SELECT * FROM utilisateur";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute();
        $users = [];
        while ($row = $stmt->fetch()) {
            $users[] = $this->mapToUser($row);
        }
        return $users;
    }
    private function mapToUser(array $row): UserModel
    {
        return new UserModel([
            'idUser'        => $row['id_user'] ?? null,
            'nom'           => $row['nom'] ?? null,
            'prenom'        => $row['prenom'] ?? null,
            'email'         => $row['email'] ?? null,
            'mdp'           => $row['mdp'] ?? null,
            'role'          => $row['role'] ?? null,
            'refValidateur' => $row['ref_validateur'] ?? null,
        ]);
    }
}
