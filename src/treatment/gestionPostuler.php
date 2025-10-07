<?php

require_once ('../bdd/config.php');
$pdo  = (new Config())->connexion();
$email = "";
$ref_offre = "";
$lettre = "";
$est_accepte = "";

    $ref_offre =(int) $_POST['ref_offre'];
    $email = $_POST['email'];
    $est_accepte = 1 ;
    $lettre   = $_POST['lettre'] ?? '';

//Chercher mail user
$sql =$pdo->query("SELECT * FROM utilisateur where  email = ? ;");
$sql -> execute([$email]);
$candidat= $sql -> fetch(PDO::FETCH_ASSOC);
$ref_user = (int)($candidat['id_user']);

// Insertion BDD
    $sql = $pdo->prepare(" INSERT INTO postuler (ref_user,ref_offre,motivation,est_accepte)  VALUES (?, ?, ?, ?)
");
    $ok = $sql->execute(["ref_user"=> $ref_user,
        "ref_offre"=>$ref_offre,"Lettre de motivation"=> $lettre,"Est_accepte"=> $est_accepte]);
    if($ok){
       echo "Ok"; // redirige vers une page de remerciement

    }

?>

