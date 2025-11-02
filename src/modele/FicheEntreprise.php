<?php

class FicheEntreprise
{
private $idFicheEntreprise ;
private $nomEntreprise ;
private $adresseEntreprise ;
private $adresseWeb ;
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
    public function getIdFicheEntreprise()
    {
        return $this->idFicheEntreprise;
    }

    /**
     * @param mixed $idFicheEntreprise
     */
    public function setIdFicheEntreprise($idFicheEntreprise): void
    {
        $this->idFicheEntreprise = $idFicheEntreprise;
    }

    /**
     * @return mixed
     */
    public function getNomEntreprise()
    {
        return $this->nomEntreprise;
    }

    /**
     * @param mixed $nomEntreprise
     */
    public function setNomEntreprise($nomEntreprise): void
    {
        $this->nomEntreprise = $nomEntreprise;
    }

    /**
     * @return mixed
     */
    public function getAdresseEntreprise()
    {
        return $this->adresseEntreprise;
    }

    /**
     * @param mixed $adresseEntreprise
     */
    public function setAdresseEntreprise($adresseEntreprise): void
    {
        $this->adresseEntreprise = $adresseEntreprise;
    }

    /**
     * @return mixed
     */
    public function getAdresseWeb()
    {
        return $this->adresseWeb;
    }

    /**
     * @param mixed $adresseWeb
     */
    public function setAdresseWeb($adresseWeb): void
    {
        $this->adresseWeb = $adresseWeb;
    }


}