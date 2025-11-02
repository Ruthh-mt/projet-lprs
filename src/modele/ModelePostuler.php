<?php

class ModelePostuler
{
private $refUser ;
private $refOffre ;
private $motivation ;

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
    public function getRefOffre()
    {
        return $this->refOffre;
    }

    /**
     * @param mixed $refOffre
     */
    public function setRefOffre($refOffre): void
    {
        $this->refOffre = $refOffre;
    }

    /**
     * @return mixed
     */
    public function getMotivation()
    {
        return $this->motivation;
    }

    /**
     * @param mixed $motivation
     */
    public function setMotivation($motivation): void
    {
        $this->motivation = $motivation;
    }

}