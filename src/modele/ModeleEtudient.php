<?php

class ModeleEtudient extends ModeleUtilisateur
{
    private $ref_user;
    private $annee_promo;
    public function __construct(PDO $db, array $donnees){
        parent::__construct($db);
        $this->hydrate($donnees);

    }

    public function hydrate(array $donnees){
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
        return $this->ref_user;
    }

    /**
     * @param mixed $ref_user
     */
    public function setRefUser($ref_user): void
    {
        $this->ref_user = $ref_user;
    }

    /**
     * @return mixed
     */
    public function getAnneePromo()
    {
        return $this->annee_promo;
    }

    /**
     * @param mixed $annee_promo
     */
    public function setAnneePromo($annee_promo): void
    {
        $this->annee_promo = $annee_promo;
    }




}