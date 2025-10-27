<?php
class PostRepository
{
    private $db;
    public function __construct(){
        $this->db=New Config();
    }

    public function getAllPost(){
        $sql="SELECT * FROM post";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function createPost($post){
        $ajout="INSERT INTO post(titre_post,contenu_post,date_heure_post,ref_user) VALUES (:titrePost,:contenuPost,:dateHeurePost,:refUser)";
        $stmt=$this->db->connexion()->prepare($ajout);
        $stmt->execute([
            "titrePost" => $post->getTitrePost(),
        "contenuPost" => $post->getContenuPost(),
        "dateHeurePost" => $post->getDateHeurePost(),
            "refUser" =>  $post->getRefUser()
        ]);

    }
    public function findUsername($post){
        $sql="SELECT utilisateur.nom,utilisateur.prenom FROM post WHERE idPost=:idPost INNER JOIN  utilisateur ON utilisateur.idUtilisateur=post.refUser ";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute(["idPost" => $post->getIdPost()]);
        return $stmt->fetch();
    }


}