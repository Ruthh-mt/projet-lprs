<?php

class PostRepository
{
    private $db;

    public function __construct()
    {
        $this->db = new Config();
    }

    public function getAllPostByCanal($post)
    {
        $sql = "SELECT * FROM post WHERE canal=:canal ORDER BY date_heure_post DESC";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(["canal" => $post->getCanal()]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function createPost($post)
    {
        $ajout = "INSERT INTO post(titre_post,canal,contenu_post,date_heure_post,ref_user) VALUES (:titrePost,:canal,:contenuPost,:dateHeurePost,:refUser)";
        $stmt = $this->db->connexion()->prepare($ajout);
        $stmt->execute([
            "titrePost" => $post->getTitrePost(),
            "canal" => $post->getCanal(),
            "contenuPost" => $post->getContenuPost(),
            "dateHeurePost" => $post->getDateHeurePost(),
            "refUser" => $post->getRefUser()
        ]);
    }

    public function UpdatePost($post)
    {
        $ajout = "UPDATE post SET titre_post=:titrePost,canal=:canal,contenu_post=:contenuPost WHERE id_post=:idPost";
        $stmt = $this->db->connexion()->prepare($ajout);
        $stmt->execute([
            "idPost" => $post->getIdPost(),
            "titrePost" => $post->getTitrePost(),
            "canal" => $post->getCanal(),
            "contenuPost" => $post->getContenuPost(),
        ]);
    }

    public function findUsername($idPost)
    {
        $sql = "SELECT utilisateur.nom,utilisateur.prenom FROM post INNER JOIN  utilisateur ON post.ref_user = utilisateur.id_user WHERE id_post=:idPost";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(["idPost" => $idPost]);
        return $stmt->fetch();
    }

    public function getPostById($post)
    {
        $sql = "SELECT * FROM post WHERE id_post=:idPost";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(["idPost" => $post->getidPost()]);
        $req = $stmt->fetch();
        $post->setIdPost($req['id_post']);
        $post->setTitrePost($req['titre_post']);
        $post->setCanal($req['canal']);
        $post->setContenuPost($req['contenu_post']);
        $post->setDateHeurePost($req['date_heure_post']);
        $post->setRefUser($req['ref_user']);
        return $post;
    }

    public function deletePost($post)
    {
        $delete = "DELETE FROM post WHERE id_post=:idPost";
        $stmt = $this->db->connexion()->prepare($delete);
        $stmt->execute(["idPost" => $post->getIdPost()]);
    }


    public function getAllPostByUser($post)
    {
        $sql = "SELECT * FROM post WHERE ref_user=:refUser";
        $stmt = $this->db->connexion()->prepare($sql);
        $stmt->execute(["refUser" => $post->getRefUser()]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


}