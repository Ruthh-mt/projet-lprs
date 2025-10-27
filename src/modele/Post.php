<?php
class Post
{
private $idPost;
private $titrePost;
private $contenuPost;
private $dateHeurePost;
private $canal;
private $refUser;

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
    public function getIdPost()
    {
        return $this->idPost;
    }

    /**
     * @param mixed $idPost
     */
    public function setIdPost($idPost): void
    {
        $this->idPost = $idPost;
    }

    /**
     * @return mixed
     */
    public function getTitrePost()
    {
        return $this->titrePost;
    }

    /**
     * @param mixed $titrePost
     */
    public function setTitrePost($titrePost): void
    {
        $this->titrePost = $titrePost;
    }

    /**
     * @return mixed
     */
    public function getContenuPost()
    {
        return $this->contenuPost;
    }

    /**
     * @param mixed $contenuPost
     */
    public function setContenuPost($contenuPost): void
    {
        $this->contenuPost = $contenuPost;
    }

    /**
     * @return mixed
     */
    public function getDateHeurePost()
    {
        return $this->dateHeurePost;
    }

    /**
     * @param mixed $dateHeurePost
     */
    public function setDateHeurePost($dateHeurePost): void
    {
        $this->dateHeurePost = $dateHeurePost;
    }

    /**
     * @return mixed
     */
    public function getCanal()
    {
        return $this->canal;
    }

    /**
     * @param mixed $canal
     */
    public function setCanal($canal): void
    {
        $this->canal = $canal;
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