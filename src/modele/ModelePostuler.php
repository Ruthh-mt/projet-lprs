<?php

class ModelePostuler
{
    private $refUser;
    private $refOffre;
    private $motivation;

    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }

    private function toCamelCase($string)
    {
        $string = strtolower($string);
        $string = str_replace('_', ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);
        return lcfirst($string);
    }

    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) {

            $camel = $this->toCamelCase($key); // ref_user → refUser

            $method = 'set'.ucfirst($camel);   // → setRefUser()

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function getRefUser()      { return $this->refUser; }
    public function setRefUser($v)    { $this->refUser = $v; }

    public function getRefOffre()     { return $this->refOffre; }
    public function setRefOffre($v)   { $this->refOffre = $v; }

    public function getMotivation()   { return $this->motivation; }
    public function setMotivation($v) { $this->motivation = $v; }
}
