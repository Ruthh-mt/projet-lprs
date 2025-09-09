<?php
class Config
{
     private $nomBDD = 'lprs';
     private $serveur = 'localhost';
     private $user = 'root';
     private $password = '';
     private $bdd;

     public function __construct()
     {
          try {
               $this->bdd = new PDO(
                    "mysql:host=" . $this->serveur . ";dbname=" . $this->nomBDD . ";charset=utf8",
                    $this->user,
                    $this->password,
                    [
                         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                         PDO::ATTR_EMULATE_PREPARES => false
                    ]
               );
          } catch (PDOException $e) {
               die("Erreur de connexion à la base de données : " . $e->getMessage());
          }
     }

     public function connexion()
     {
          return $this->bdd;
     }
}
?>