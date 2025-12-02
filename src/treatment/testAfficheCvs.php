

<?php
$chemin_telechargement = __DIR__  .'/telechargement/candidatures/';
//Verifier fichier est present dans le dossier
if(isset($_POST['cv'])) {
    $file_nom = $_POST['cv'];
    echo "fichier : ".$file_nom;
    echo "<br>";
    if (file_exists($chemin_telechargement . $file_nom)) {
        echo "Fichier ".$file_nom." Existe";
    } else {
        echo "Fichier pas trouvé !!!";
    }
}



?>