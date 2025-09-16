<?php
class UsersModel
{
     private ?int $id_user = null;
     private ?string $prenom = null;
     private ?string $nom = null;
     private ?string $telephone = null;
     private ?string $email = null;
     private ?string $mdp = null;
     private ?string $est_valide = null;

     public function __construct(array $donnees = [])
     {
          $this->hydrate($donnees);
     }

     public function hydrate($data) {
          if (isset($data['id_utilisateur'])) {
               $this->id_user = $data['id_utilisateur'];
          }
          if (isset($data['prenom'])) {
               $this->prenom = $data['prenom'];
          }
          if (isset($data['nom'])) {
               $this->nom = $data['nom'];
          }
          if (isset($data['telephone'])) {
               $this->telephone = $data['telephone'];
          }
          if (isset($data['email'])) {
               $this->email = $data['email'];
          }
          if (isset($data['mot_de_passe'])) {
               $this->mdp = $data['mdp'];
          }
          if (isset($data['date_naissance'])) {
               $this->date_naissance = $data['date_naissance'];
          }
          if (isset($data['inscription'])) {
               $this->inscription = new DateTime($data['inscription']);
          }
          if (isset($data['role'])) {
               $this->role = $data['role'];
          }

     }

     public function getMdp(): ?string
     {
          return $this->mdp;
     }

     public function setMdp(?string $mdp): void
     {
          $this->mdp = $mdp;
     }

     public function getEmail(): ?string
     {
          return $this->email;
     }

     public function setEmail(?string $email): void
     {
          $this->email = $email;
     }

     public function getTelephone(): ?string
     {
          return $this->telephone;
     }

     public function setTelephone(?string $telephone): void
     {
          $this->telephone = $telephone;
     }

     public function getNom(): ?string
     {
          return $this->nom;
     }

     public function setNom(?string $nom): void
     {
          $this->nom = $nom;
     }

     public function getPrenom(): ?string
     {
          return $this->prenom;
     }

     public function setPrenom(?string $prenom): void
     {
          $this->prenom = $prenom;
     }

     public function getIdUser(): ?int
     {
          return $this->id_user;
     }

     public function setIdUser(?int $id_user): void
     {
          $this->id_user = $id_user;
     }

     public function getEstValide(): ?string
     {
          return $this->est_valide;
     }

     public function setEstValide(?string $est_valide): void
     {
          $this->est_valide = $est_valide;
     }

}
