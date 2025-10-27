<?php
require_once "../bdd/config.php";
require_once "../modele/UserModel.php";
require_once "../repository/utilisateurRepository.php";

$mdpnew=htmlspecialchars($_POST['mdp']);
$mdpConfirm=htmlspecialchars($_POST['confirmation']);
if(isset($_POST['token'])&&isset($mdpnew)&&isset($mdpConfirm)){

    $config = new Config();
    $pdo = $config->connexion();
    if($mdpnew==$mdpConfirm) {
        $mdp = password_hash($mdpnew, PASSWORD_DEFAULT);
        $token = $_POST['token'];
        $repo = new utilisateurRepository();
        $verif = $repo->verifierToken($token);
        if ($verif) {
            $email = $verif["email"];
            $repo->changerMdp($mdp, $email);
            echo "<h3>Mot de passe modifié</h3>";
            echo "<p>Fermez cette page</p>";


        }
    }else{
        echo"La confirmation du  mot de passe est differente du mot de passe";
    }

}else {
    $token=$_POST['token'];
    echo"veuillez remplir tous les champs";
    header("Location:../../view/reinitialiserMdp.php/?token='.$token");
}
?>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4; /* Fond blanc */
        margin: 20px;
        padding: 0;
        color: #333;
        text-align: center;
    }

    .logo {
        font-size: 1.8em;
        font-weight: bold;
    }

    nav a {
        margin: 0 15px;
        text-decoration: none;
        color: #333;
        font-size: 1.2em;
        transition: 0.3s;
    }

    .banner {
        background: url('https://source.unsplash.com/1600x600/?cinema,movie') no-repeat center;
        background-size: cover;
        color: black;
        padding: 80px 20px;
    }

    .banner h1 {
        font-size: 2.5em;
        margin-bottom: 10px;
    }

    .banner p {
        font-size: 1.2em;
    }

    /* Section des films */
    h2 {
        margin-top: 40px;
        font-size: 2em;
    }

    .film-grid {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .film-card {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 15px;
        width: 250px;
        text-align: center;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .film-card:hover {
        transform: scale(1.05);
    }

    .film-card img {
        width: 100%;
        border-radius: 8px;
    }

    .film-card h3 {
        margin-top: 10px;
        font-size: 1.2em;
    }
    footer {
        background: #f1f1f1;
        padding: 15px;
        margin-top: 40px;
        font-size: 0.9em;
    }
</style>
