<?php

class ModeleAvis
{
    private $idAvis;
    private $note;
    private $commentaire;
    private $dateAvis;
    private $refUser;
    private $refEvenement;

    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }

    private function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function getIdAvis()
    {
        return $this->idAvis;
    }

    public function setIdAvis($idAvis): void
    {
        $this->idAvis = $idAvis;
    }

    public function getNote()
    {
        return $this->note;
    }

    public function setNote($note): void
    {
        $this->note = $note;
    }

    public function getCommentaire()
    {
        return $this->commentaire;
    }

    public function setCommentaire($commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    public function getDateAvis()
    {
        return $this->dateAvis;
    }

    public function setDateAvis($dateAvis): void
    {
        $this->dateAvis = $dateAvis;
    }

    public function getRefUser()
    {
        return $this->refUser;
    }

    public function setRefUser($refUser): void
    {
        $this->refUser = $refUser;
    }

    public function getRefEvenement()
    {
        return $this->refEvenement;
    }

    public function setRefEvenement($refEvenement): void
    {
        $this->refEvenement = $refEvenement;
    }
}
