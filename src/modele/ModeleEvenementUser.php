<?php

class ModeleEvenementUser
{
    private $refUser;
    private $refEvenement;
    private $estSuperviseur;

    public function __construct(array $donnee)
    {
        $this->hydrate($donnee);
    }

    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getRefUser()
    {
        return $this->refUser;
    }

    /**
     * @param mixed $refUser
     */
    public function setRefUser($refUser): void
    {
        $this->refUser = $refUser;
    }

    /**
     * @return mixed
     */
    public function getRefEvenement()
    {
        return $this->refEvenement;
    }

    /**
     * @param mixed $refEvenement
     */
    public function setRefEvenement($refEvenement): void
    {
        $this->refEvenement = $refEvenement;
    }

    /**
     * @return mixed
     */
    public function getEstSuperviseur()
    {
        return $this->estSuperviseur;
    }

    /**
     * @param mixed $estSuperviseur
     */
    public function setEstSuperviseur($estSuperviseur): void
    {
        $this->estSuperviseur = $estSuperviseur;
    }



}