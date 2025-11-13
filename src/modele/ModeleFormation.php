<?php

class ModeleFormation
{
    private $idFormation;
    private $nom;

    public function __construct(array $donnees = [])
    {
        if (!empty($donnees)) {
            $this->hydrate($donnees);
        }
    }

    public function hydrate(array $donnees): void
    {
        foreach ($donnees as $key => $value) {
            switch ($key) {
                case 'id_formation':
                case 'idFormation':
                    $this->setIdFormation($value);
                    break;

                case 'nom':
                    $this->setNom($value);
                    break;
            }
        }
    }
    public function getIdFormation()
    {
        return $this->idFormation;
    }

    public function setIdFormation($idFormation): void
    {
        $this->idFormation = (int) $idFormation;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom): void
    {
        $this->nom = $nom;
    }
}
