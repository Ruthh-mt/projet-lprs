<?php

require_once __DIR__ . '/../bdd/config.php';

class MdpResetRepository
{

    private PDO $db;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo instanceof PDO) {
            $this->db = $pdo;
        } else {
            $this->db = (new Config())->connexion();
        }
    }
    public function createToken($mdpReset){
        $sql = "INSERT INTO mdp_reset (token,expire_a,ref_user) VALUES(:token,:expireA,:refUser)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $mdpReset->getToken(),
            "expireA"=>$mdpReset->getExpireA(),
            "refUser"=>$mdpReset->getRefUser()]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function verifierToken($mdpReset){
        $sql = "SELECT utilisateur.email FROM mdp_reset inner join utilisateur on utilisateur.id_user=mdp_reset.ref_user WHERE  token=:token";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $mdpReset->getToken()]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function deleteToken($mdpReset){
        $sql = "delete FROM mdp_reset WHERE  token=:token";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $mdpReset->getToken()]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}