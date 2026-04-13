<?php

require_once __DIR__ . '/../bdd/Config.php';

class MdpResetRepository
{

    private Config $db;

    public function __construct()
    {
            $this->db = new Config();

    }
    public function createToken($mdpReset){
        $sql = "INSERT INTO mdp_reset (token,expire_a,ref_user) VALUES(:token,:expireA,:refUser)";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(["token" => $mdpReset->getToken(),
            "expireA"=>$mdpReset->getExpireA(),
            "refUser"=>$mdpReset->getRefUser()]);
        return $this->db->connexion()->lastInsertId();
    }
    public function verifierToken($mdpReset){
        $sql = "SELECT U.email,MDP.expire_a FROM mdp_reset as MDP inner join utilisateur as U on U.id_user=MDP.ref_user WHERE  MDP.token=:token";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(['token' => $mdpReset->getToken()]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function deleteToken($mdpReset){
        $sql = "delete FROM mdp_reset WHERE  token=:token";
        $stmt = $this->db->connexion()->prepare($sql);
        return $stmt->execute(['token' => $mdpReset->getToken()]);

    }
}