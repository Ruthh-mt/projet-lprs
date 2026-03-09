<?php
require_once "../bdd/Config.php";
require_once "../modele/ModeleMdpReset.php";
require_once "../repository/MdpResetRepository.php";
require_once "../modele/ModeleUtilisateur.php";
require_once "../repository/UtilisateurRepository.php";

function redirectWith(string $type, string $message, string $target): void
{
    $_SESSION['toastr'] = [
            "type" => $type,
            "message" => $message,
    ];
    session_write_close();
    header("Location: $target", $_SESSION["toastr"]["type"]);
    exit();
}


$mdpnew="";
$mdpConfirm="";
$token = "";
if(isset($_POST["token"]) && isset($_POST["mdp"]) && isset($_POST["confirmation"])){
    $mdpnew=htmlspecialchars($_POST['mdp']);
    $mdpConfirm=htmlspecialchars($_POST['confirmation']);
    $token = $_POST['token'];
    $config = new Config();
    $repoUser=new UtilisateurRepository();
    if($mdpnew==$mdpConfirm) {
        $mdp = password_hash($mdpnew, PASSWORD_DEFAULT);
        $mdpReset=new ModeleMdpReset(["token"=>$token]);
        $repo = new MdpResetRepository();
        try {
            $verif = $repo->verifierToken($mdpReset);
            if ($verif) {
                $email = $verif->email;
                $success = $repoUser->changerMdp($mdp, $email);
                if ($success) {
                    $repo->deleteToken($mdpReset);
                    redirectWith("success", "Le mot de passe a bien été modifié", "../../view/connexion.php");
                } else {
                    redirectWith("error", "Le mot de passe n'a pas été modifié. Veuillez réessayer", '../../view/reinitialiserMdp.php?token=' . $token);
                }
            }else{
                redirectWith("error","Le token est invalide ou a expiré","../../view/connexion.php");
            }
        }catch(PDOException $e){
            redirectWith("error", $e->getMessage(),"../../view/reinitialiserMdp.php?token=' . $token");

        }

    }else{
        redirectWith("error","Le mot de passe et la confirmation du mot de passe sont different",' ../../view/reinitialiserMdp.php?token=' . $token);
    }

}else {
    echo"veuillez remplir tous les champs";
    redirectWith("error","Veuillez remplir tous les champs",'../../view/reinitialiserMdp.php?token=' . $token);
}

