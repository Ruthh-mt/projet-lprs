<?php

class ModeleMdpReset
{
    private $idMdpReset;
    private $token;
    private $expireA;
    private $refUser;

    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
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
    public function getIdMdpReset()
    {
        return $this->idMdpReset;
    }

    /**
     * @param mixed $idMdpReset
     */
    public function setIdMdpReset($idMdpReset): void
    {
        $this->idMdpReset = $idMdpReset;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getExpireA()
    {
        return $this->expireA;
    }

    /**
     * @param mixed $expireA
     */
    public function setExpireA($expireA): void
    {
        $this->expireA = $expireA;
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


}