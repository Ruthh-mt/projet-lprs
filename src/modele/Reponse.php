<?php

class Reponse
{
    private $idReponse;
    private $contenuReponse;
    private $dateHeureReponse;
    private $refUser;
    private $ref_post;

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
    public function getIdReponse()
    {
        return $this->idReponse;
    }

    /**
     * @param mixed $idReponse
     */
    public function setIdReponse($idReponse): void
    {
        $this->idReponse = $idReponse;
    }

    /**
     * @return mixed
     */
    public function getContenuReponse()
    {
        return $this->contenuReponse;
    }

    /**
     * @param mixed $contenuReponse
     */
    public function setContenuReponse($contenuReponse): void
    {
        $this->contenuReponse = $contenuReponse;
    }

    /**
     * @return mixed
     */
    public function getDateHeureReponse()
    {
        return $this->dateHeureReponse;
    }

    /**
     * @param mixed $dateHeureReponse
     */
    public function setDateHeureReponse($dateHeureReponse): void
    {
        $this->dateHeureReponse = $dateHeureReponse;
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
    public function getRefPost()
    {
        return $this->ref_post;
    }

    /**
     * @param mixed $ref_post
     */
    public function setRefPost($ref_post): void
    {
        $this->ref_post = $ref_post;
    }

}