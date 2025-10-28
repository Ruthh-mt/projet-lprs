<?php
class UserModel
{
    private $idUser;
    private $nom;
    private $prenom;
    private $email;
    private $mdp;
    private $role;
    private $ref_validateur;

    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }

    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) {

            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {

                $this->$method($value);
            }
        }
    }
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * @param mixed $mdp
     */
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }
    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param mixed $idUser
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }


     public function getUserById(int $id_user): ?array
     {
          $sql = "SELECT * FROM utilisateur WHERE id_user = :id_user";
          $stmt = $this->db->prepare($sql);
          $stmt->execute(['id_user' => $id_user]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          return $result ?: null;
     }
     public function getUserByEmail(string $email): ?array
     {
          $sql = "SELECT * FROM utilisateur WHERE email = :email";
          $stmt = $this->db->prepare($sql);
          $stmt->execute(['email' => $email]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          return $result ?: null;
     }
     public function createUser(array $data): bool
     {
          $sql = "INSERT INTO utilisateur (id_user, nom, prenom, email, mdp, est_valide) 
                VALUES (:id_user, :nom, :prenom, :email, :mdp, :est_valide)";

          $stmt = $this->db->prepare($sql);

          return $stmt->execute([
               'id_user'   => $data['id_user'],
               'nom'       => $data['nom'],
               'prenom'    => $data['prenom'],
               'email'     => $data['email'],
               'mdp'       => password_hash($data['mdp'], PASSWORD_BCRYPT),
               'est_valide'=> $data['est_valide'] ?? 0,
          ]);
     }

     public function updateUser(int $id_user, array $data): bool
     {
          $sql = "UPDATE utilisateur 
                SET nom = :nom, prenom = :prenom, email = :email, est_valide = :est_valide
                WHERE id_user = :id_user";

          $stmt = $this->db->prepare($sql);

          return $stmt->execute([
               'id_user'   => $id_user,
               'nom'       => $data['nom'],
               'prenom'    => $data['prenom'],
               'email'     => $data['email'],
               'est_valide'=> $data['est_valide'] ?? 0,
          ]);
     }

     public function deleteUser(int $id_user): bool
     {
          $sql = "DELETE FROM utilisateur WHERE id_user = :id_user";
          $stmt = $this->db->prepare($sql);
          return $stmt->execute(['id_user' => $id_user]);
     }

     public function getAllUsers(): array
     {

          $sql = "SELECT * FROM utilisateur";
          $stmt = $this->db->query($sql);
          return $stmt->fetchAll(PDO::FETCH_ASSOC);
     }
}
