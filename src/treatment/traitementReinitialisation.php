<?php
require_once "../bdd/config.php";
require_once "../modele/UserModel.php";
require_once "../repository/userRepository.php";

if(isset($_POST['token'])&&isset($_POST['mdp'])&&isset($_POST['confirmation'])){

    $config = new Config();
    $pdo = $config->connexion();
    if($_POST['mdp']==$_POST['confirmation']) {
        $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
        $token = $_POST['token'];
        $repo = new UserRepository($pdo);
        $verif = $repo->verifierToken($token);
        if ($verif) {
            $email = $verif["email"];
            $repo->changerMdp($mdp, $email);
            echo "mdp mis a jour";

        }
    }else{
        echo"La confirmation du  mot de passe est differente du mot de passe";
        header("Location:../../vue/connexion.php");
    }

}else {
    echo"veuillez remplir tous les champs";
    header("Location:../../vue/connexion.php");
}