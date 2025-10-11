<?php

require_once ('../bdd/config.php');
$pdo  = (new Config())->connexion();


    $ref_offre =(int) $_POST['ref_offre'];
    $email = $_POST['email'];
    $est_accepte = 1 ;
    $lettre   = $_POST['lettre'] ?? '';

//Chercher mail user
$sql =$pdo->prepare("SELECT * FROM utilisateur where  email = ? ;");
$sql -> execute([$email]);
$candidat= $sql -> fetch(PDO::FETCH_ASSOC);
$ref_user = (int)($candidat['id_user']);
if ($candidat) {

    // -------------------------------- Gestion du CV-----------------------------------------

// Dossiers de stockage
    $chemin_telechargement = __DIR__ . '/telechargement/candidatures/';    if (!is_dir($chemin_telechargement)) {
        mkdir($chemin_telechargement, 0777, true);
    }

// Initialiser les chemins
    $lien_cv = null;


// Nettoyer nom et prénom pour éviter les caractères spéciaux dans le nom de fichier
    $nettoyer_nom   = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($candidat['nom']));
    $nettoyer_prenom = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($candidat['prenom']));


    if (!empty($_FILES['cv']['name'])) {
        $cvTmp = $_FILES['cv']['tmp_name'];
        $extension = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);

        // Nom du fichier : cv_nom_prenom.pdf
        $nom_cv = "cv_{$nettoyer_nom}_{$nettoyer_prenom}." . $extension;
        $cvDest = $chemin_telechargement . $nom_cv;

        if (move_uploaded_file($cvTmp, $cvDest)) {
            $lien_cv = "/telechargement/candidatures/" . $nom_cv;        }
    }



// Insertion BDD
    $sql = $pdo->prepare(" INSERT INTO postuler (ref_user,ref_offre,motivation,est_accepte)  VALUES (?, ?, ?, ?)
");
    $ok = $sql->execute([$ref_user, $ref_offre, $lettre, $est_accepte]);
    if($ok){
        header("Location: ../../view/redirection_postuler.php");
        exit;

    }

}
else{
    die("Erreur");
}

?>

