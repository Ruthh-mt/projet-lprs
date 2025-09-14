
<?php

require_once '../bdd/config.php';


echo "<table>";
echo '<form action ="verification_email.php" method="post">';
echo    '<tr>';
echo        '<td><label for name = "email">Adresse mail pour reinitialiser mot de passe</label></td>';
echo        '<td> <input type ="text" name ="email"></td>';
echo        '<td><input type ="submit" value="Valider"></td>';

echo    '</tr>';
echo    '</form>'    ;
echo    '</table>'    ;



$config = new Config();
$pdo = $config->connexion();
$email = "";
if(isset($_POST['email'])){
    $email = $_POST['email'];
}

//1 -  Si cet email existe dans la base, un token sécurisé est généré et stocké temporairement dans la base
$sqlEmail = $pdo -> prepare("SELECT * from utilisateur where email = ?");
$sqlEmail -> execute([$email]) ;
$ligne = $sqlEmail -> fetch();
if($ligne){
    echo "Cet email existe";
    $token = bin2hex(random_bytes(32));
    date_default_timezone_set('Europe/Paris');
    $expire_a = date("Y-m-d H:i:s", strtotime('+1 hour'));
    $sqlToken =$pdo->prepare( "INSERT INTO mdp_reset( token, expire_a, ref_user) VALUES (?,?,?)" );
    $sqlToken -> execute([$token,$expire_a,$ligne['id_user']]);

    //Un email contenant un lien unique est envoyé à l’utilisateur.
}





?>