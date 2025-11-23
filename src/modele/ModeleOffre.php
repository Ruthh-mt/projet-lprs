<?php

class ModeleOffre
{
    private $idOffre;
    private $titreOffre;
    private $description;
    private $mission;
    private $salaire;
    private $typeContrat;
    private $etat;
    private $refFiche;

    public function __construct(array $donnees){
        $this->hydrate($donnees);
    }

    private function hydrate(array $donnees){
        foreach ($donnees as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function getIdOffre()
    {
        return $this->idOffre;
    }

    public function setIdOffre($idOffre): void
    {
        $this->idOffre = $idOffre;
    }

    public function getTitreOffre()
    {
        return $this->titreOffre;
    }

    public function setTitreOffre($titreOffre): void
    {
        $this->titreOffre = $titreOffre;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getMission()
    {
        return $this->mission;
    }

    public function setMission($mission): void
    {
        $this->mission = $mission;
    }

    public function getSalaire()
    {
        return $this->salaire;
    }

    public function setSalaire($salaire): void
    {
        $this->salaire = $salaire;
    }

    public function getEtat()
    {
        return $this->etat;
    }

    public function setEtat($etat): void
    {
        $this->etat = $etat;
    }

    public function getTypeContrat()
    {
        return $this->typeContrat;
    }


    public function setTypeContrat($typeContrat): void
    {
        $this->typeContrat = $typeContrat;
    }

    public function getRefFiche()
    {
        return $this->refFiche;
    }

    public function setRefFiche($refFiche): void
    {
        $this->refFiche = $refFiche;
    }
}
