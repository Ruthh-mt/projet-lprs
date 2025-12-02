<?php

class ModeleFicheEntreprise
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

    public function getIdFicheEntreprise()
    {
        return $this->idFicheEntreprise;
    }

    public function setIdFicheEntreprise($idFicheEntreprise): void
    {
        $this->idFicheEntreprise = $idFicheEntreprise;
    }

    public function getNomEntreprise()
    {
        return $this->nomEntreprise;
    }

    public function setNomEntreprise($nomEntreprise): void
    {
        $this->nomEntreprise = $nomEntreprise;
    }

    public function getAdresseEntreprise()
    {
        return $this->adresseEntreprise;
    }

    public function setAdresseEntreprise($adresseEntreprise): void
    {
        $this->adresseEntreprise = $adresseEntreprise;
    }

    public function getAdresseWeb()
    {
        return $this->adresseWeb;
    }

    public function setAdresseWeb($adresseWeb): void
    {
        $this->adresseWeb = $adresseWeb;
    }


}