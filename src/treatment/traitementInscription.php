<?php

use Couchbase\User;

require_once "../repository/userRepository.php";
require_once "../bdd/config.php";
require_once "../modele/userModel.php";
var_dump($_POST);
$config = new Config();
$bdd = $config ->connexion() ;

if(isset($_POST['nom'])&&$_POST['prenom']&&$_POST['email']&&$_POST['mdp'] && $_POST['role'] ){


      $user = new userModel(array(
          'nom' => $_POST['nom'],
              'prenom' => $_POST['prenom'],
              'email' => $_POST['email'],
              'mdp' => password_hash($_POST['mdp'], PASSWORD_DEFAULT),
              'role' => $_POST['role']

          )
      );
        var_dump($user);
        $repository = new userRepository($bdd);

        $repository->inscription($user);
        var_dump($_POST);

}
?>
