<?php

class Offre
{
 private $idOffre ;
 private $titre ;
 private $description ;
 private $mission ;
 private $salaire ;
 private $type ;
 private $etat ;
 private $refFiche ;
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


    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre): void
    {
        $this->titre = $titre;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
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
        return $this->salaire;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat): void
    {
        $this->etat = $etat;
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
    public function setIdOffre($idOffre): void
    {
        $this->idOffre = $idOffre;
    }

    /**
     * @return mixed
     */
    public function getRefFiche()
    {
        return $this->refFiche;
    }

    /**
     * @param mixed $refFiche
     */
    public function setRefFiche($refFiche): void
    {
        $this->refFiche = $refFiche;
    }

    /**
     * @return mixed
     */

}