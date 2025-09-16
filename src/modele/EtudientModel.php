<?php

class EtudientModel extends userModel
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


}