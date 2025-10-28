<?php

class OffreModel
{

    private $titreOffre;
    private $description;
    private $mission;
    private $salaire;
    private $typeContrat;
    private $etat;
    private $ref_fiche ;


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

    /**
     * @return mixed
     */
    public function getIdOffre()
    {
        return $this->idOffre;
    }

    /**
     * @param mixed $idOffre
     */
    public function setIdOfre($idOffre): void
    {
        $this->idOffre = $idOffre;
    }

    /**
     * @return mixed
     */
    public function getTitreOffre()
    {
        return $this->titreOffre;
    }

    /**
     * @param mixed $titreOffre
     */
    public function setTitreOffre($titreOffre): void
    {
        $this->titreOffre = $titreOffre;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description ;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * @param mixed $mission
     */
    public function setMission($mission): void
    {
        $this->mission = $mission;
    }

    /**
     * @return mixed
     */
    public function getSalaire()
    {
        return $this->salaire ;
    }

    /**
     * @param mixed $salaire
     */
    public function setSalaire($salaire): void
    {
        $this->salaire = $salaire;
    }

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $mission
     */
    public function setEtat($etat): void
    {
        $this->etat = $etat;
    }

    /**
     * @return mixed
     */
    public function getTypeContrat()
    {
        return $this->typeContrat ;
    }
    /**
     * @param mixed $typeContrat
     */
    public function setType($typeContrat)
    {
        return $this->typeContrat = $typeContrat;
    }
    public function getRefFiche()
    {
        return $this->ref_fiche ;
    }
    /**
     * @param mixed $ref_fiche
     */
    public function setRefFiche($ref_fiche)
    {
        return $this->ref_fiche = $ref_fiche;
    }

}