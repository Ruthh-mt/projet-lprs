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
    public function findUsername($idPost){
        $sql="SELECT utilisateur.nom,utilisateur.prenom FROM post INNER JOIN  utilisateur ON post.ref_user = utilisateur.id_user WHERE id_post=:idPost";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute(["idPost" => $idPost]);
        return $stmt->fetch();
    }
    public function getPostById($post){
        $sql="SELECT * FROM post WHERE id_post=:idPost";
        $stmt=$this->db->connexion()->prepare($sql);
        $stmt->execute(["idPost" => $post->getidPost()]);
        $req=$stmt->fetch();
        $post->setIdPost($req['id_post']);
        $post->setTitrePost($req['titre_post']);
        $post->setCanal($req['canal']);
        $post->setContenuPost($req['contenu_post']);
        $post->setDateHeurePost($req['date_heure_post']);
        $post->setRefUser($req['ref_user']);
        return $post;
    }

}